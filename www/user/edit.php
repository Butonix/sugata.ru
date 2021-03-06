<?php

include __DIR__ . '/../config.php';
include mnminclude . 'html1.php';
include mnminclude . 'avatars.php';

$globals['ads'] = false;

$globals['secure_page'] = true;

check_auth_page();

// We need it because we modify headers
ob_start();

$user_levels = array('autodisabled', 'disabled', 'normal', 'special', 'blogger', 'admin', 'god');
$bio_max = 300; // Max bio length

// User recovering her password
if (!empty($_GET['login']) && !empty($_GET['t']) && !empty($_GET['k'])) {
    $time = intval($_GET['t']);
    $key = $_GET['k'];

    $user = new User();
    $user->username = clean_input_string($_GET['login']);

    if ($user->read()) {
        $now = time();
        $key2 = md5($user->id . $user->pass . $time . $site_key . get_server_name());

        if ($time > $now - 900 && $time < $now && $key == $key2) {
            $db->query('
                UPDATE users
                SET user_validated_date = NOW()
                WHERE (
                    user_id = "'.$user->id.'"
                    AND user_validated_date IS NULL
                )
                LIMIT 1;
            ');

            $current_user->Authenticate($user->username, false);

            die(header('Location: ' . get_user_uri($user->username)));
        }
    }
}
//// End recovery

// Check user, admin and authenticated user
if ($current_user->user_id > 0 && (empty($_REQUEST['login']) || $_REQUEST['login'] === $current_user->user_login)) {
    $login = $current_user->user_login;
} elseif (!empty($_REQUEST['login']) && ($current_user->user_level === 'god')) {
    $login = $db->escape($_REQUEST['login']);
    $admin_mode = true;
} else {
    if ($current_user->user_id > 0) {
        $fallback = get_user_uri($current_user->user_login);
    } else {
        $fallback = $globals['base_url'] . 'login';
    }

    die(header("Location: $fallback"));
}

$user = new User();
$user->username = $login;

if (!$user->read()) {
    not_found();
}

if ($current_user->user_id) {
    $globals['ads_branding'] = false;
}

// Enable user AdSense
// do_user_ad: 0 = noad, > 0: probability n/100
// 100 if the user is the current one
if ($current_user->user_id == $user->id && $globals['external_user_ads'] && !empty($user->adcode)) {
    $globals['user_adcode'] = $user->adcode;
    $globals['user_adchannel'] = $user->adchannel;
    $globals['do_user_ad'] = 100;
}

$error = $success = null;

try {
    $success = save_profile();
} catch (Exception $e) {
    $error = $e->getMessage();
}

do_header(_('Редактирование профиля пользователя') . ': ' . $user->username, 'profile', User::get_menu_items('profile', $user));

$form = new stdClass;
$form->hash = md5($site_key . $user->id . $current_user->user_id);
$form->admin_mode = $admin_mode;
$form->auth_link = get_auth_link();
$form->user_levels = $user_levels;
$form->avatars_enabled = is_avatars_enabled();
$form->bio_max = $bio_max;

$prefs = $user->get_prefs();

 

//редактировать username через 3 дня запретить
    if (time() - $current_user->user_date > 86400 * 3) { 
        $form->user_edit = '0';
    } else {
        $form->user_edit = '1';
    }



Haanga::Load('user/edit.html', compact('user', 'success', 'error', 'form', 'prefs'));

do_footer();



function save_profile()
{
    global $db, $user, $current_user, $globals, $admin_mode, $site_key, $bio_max;

    $form_hash = md5($site_key . $user->id . $current_user->user_id);

    if (!empty($_POST['disabledme']) && !empty($_POST['confirm']) && ($_POST['form_hash'] === $form_hash) && ($_POST['user_id'] == $current_user->user_id)) {
        $old_user_login = $user->username;
        $old_user_id = $user->id;

        $user->disable(true);

        Log::insert('user_delete', $old_user_id, $old_user_id);

        syslog(LOG_NOTICE, "Sugata, disabling $old_user_id ($old_user_login) by $current_user->user_login -> $user->username ");

        $current_user->Logout(get_user_uri($user->username));
    }

    if (empty($_POST['save_profile']) || empty($_POST['process']) || ($_POST['user_id'] != $current_user->user_id && !$admin_mode)) {
        return;
    }

    if (empty($_POST['form_hash']) || $_POST['form_hash'] != $form_hash) {
        throw new Exception(_('Управляющий ключ отсутствует'));
    }

    if (!empty($_POST['username']) && trim($_POST['username']) != $user->username) {
        $newname = trim($_POST['username']);

        if (strlen($newname) < 3) {
            throw new Exception(_('Имя слишком короткое'));
        }

        if (!check_username($newname)) {
            throw new Exception(_('Неверное имя пользователя, неподдерживаемые символы'));
        }

        if (user_exists($newname, $user->id)) {
            throw new Exception(_('Пользователь уже существует'));
        }

        $user->username = $newname;
    }

    if (!empty($_POST['bio']) || $user->bio) {
        $bio = clean_text($_POST['bio'], 0, false, $bio_max);

        if ($bio != $user->bio) {
            $user->bio = $bio;
        }
    }

    if ($user->email != trim($_POST['email']) && !check_email(trim($_POST['email']))) {
        throw new Exception(_('Неправильный адрес e-mail'));
    }

    if (!$admin_mode && trim($_POST['email']) != $current_user->user_email && email_exists(trim($_POST['email']), false)) {
        throw new Exception(_('Другой пользователь с таким e-mail уже существует'));
    }

    $user->email = trim($_POST['email']);
    $user->url = htmlspecialchars(clean_input_url($_POST['url']));

    // Check IM address
    if (!empty($_POST['public_info'])) {
        $_POST['public_info'] = htmlspecialchars(clean_input_url($_POST['public_info']));

        $public = $db->escape($_POST['public_info']);
        $im_count = intval($db->get_var("select count(*) from users where user_id != $user->id and user_level != 'disabled' and user_level != 'autodisabled' and user_public_info='$public'"));

        if ($im_count) {
            throw new Exception(_('Уже есть другой пользователь с таким же IM-адресом'));
        }
    }

    $user->phone = $_POST['phone'];
    $user->public_info = htmlspecialchars(clean_input_url($_POST['public_info']));

    if ($user->id == $current_user->user_id) {
        if (!empty($_POST['phone'])) {
            if (!preg_match('/^\+[0-9]{9,16}$/', $_POST['phone'])) {
                throw new Exception(_('Неверный номер телефона'));
            }

            $phone = $db->escape($_POST['phone']);
            $phone_count = intval($db->get_var("select count(*) from users where user_id != $user->id and user_level != 'disabled' and user_level != 'autodisabled' and user_phone='$phone'"));

            if ($phone_count) {
                throw new Exception(_('Уже есть другой пользователь с таким же номером'));
            }
        }

        $user->phone = $_POST['phone'];
    }

    // Verifies adsense code
    if ($globals['external_user_ads']) {
        $_POST['adcode'] = trim($_POST['adcode']);
        $_POST['adchannel'] = trim($_POST['adchannel']);

        if (!empty($_POST['adcode']) && $user->adcode != $_POST['adcode']) {
            if (!preg_match('/pub\-[0-9]{16}$/', $_POST['adcode'])) {
                throw new Exception(_('Неверный код AdSense'));
            }

            $adcode_count = intval($db->get_var("select count(*) from users where user_id != $user->id and user_level != 'disabled' and user_level != 'autodisabled' and user_adcode='" . $_POST['adcode'] . "'"));

            if ($adcode_count) {
                throw new Exception(_('Уже есть другой пользователь с той же учетной записью'));
            }
        }

        if (!empty($_POST['adcode']) && !empty($_POST['adchannel']) && $user->adchannel != $_POST['adchannel']) {
            if (!preg_match('/^[0-9]{10,12}$/', $_POST['adchannel'])) {
                throw new Exception(_('Неверный канал AdSense'));
            }
        }

        $user->adcode = $_POST['adcode'];
        $user->adchannel = $_POST['adchannel'];
    }

    $user->names = clean_text($_POST['names']);

    if (!empty($_POST['password']) || !empty($_POST['password2'])) {
        if (!check_password($_POST["password"])) {
            throw new Exception(_('Ключ слишком короткий, должен состоять из 6 или более символов и содержать прописные, строчные и цифры'));
        }

        if (trim($_POST['password']) !== trim($_POST['password2'])) {
            throw new Exception(_('Пароли не совпадают'));
        }

        $new_pass = trim($_POST['password']);
        $user->pass = UserAuth::hash($new_pass);
    } else {
        $new_pass = false;
    }

    $user->comment_pref = intval($_POST['comment_pref']) + (intval($_POST['show_friends']) & 1) * 2 + (intval($_POST['show_2cols']) & 1) * 4;

    // Manage avatars upload
    if (!empty($_FILES['image']['tmp_name'])) {
        if (!avatars_check_upload_size('image')) {
            throw new Exception(_('Размер изображения превышает лимит'));
        }

        $avatar_mtime = avatars_manage_upload($user->id, 'image');

        if (!$avatar_mtime) {
            throw new Exception(_('Ошибка сохранения изображения'));
        }

        $user->avatar = $avatar_mtime;
    } elseif ($_POST['avatar_delete']) {
        $user->avatar = 0;
        avatars_remove($user->id);
    }

    // Reset avatar for the logged user
    if ($current_user->user_id == $user->id) {
        $current_user->user_avatar = $user->avatar;
    }

    if (empty($user->ip)) {
        $user->ip = $globals['user_ip'];
    }

    if ($admin_mode && !empty($_POST['user_level'])) {
        if ($user->level != $_POST['user_level']) {
            LogAdmin::insert('change_user_level', $user->id, $current_user->user_id, $user->level, $_POST['user_level']);
            AdminUser::changeLevel($user, $user->level, $_POST['user_level']);
        }

        $user->level = $db->escape($_POST['user_level']);
    }

    if ($admin_mode && !empty($_POST['karma']) && is_numeric($_POST['karma']) && $_POST['karma'] > 4 && $_POST['karma'] <= 20) {
        if ($user->karma != $_POST['karma']) {
            LogAdmin::insert('change_karma', $user->id, $current_user->user_id, $user->karma, $_POST['karma']);
        }

        $user->karma = $_POST['karma'];
    }

    $prefs = empty($_POST['prefs']) ? array() : (array)$_POST['prefs'];

    foreach (array('subs_default', 'com_order', 'last_com_first', 'use_bar', 'notify_priv') as $pref) {
        User::set_pref($user->id, $pref, in_array($pref, $prefs));
    }

    $user->store();
    $user->read();

    if (!$admin_mode && ($current_user->user_login != $user->username || $current_user->user_email != $user->email || $new_pass)) {
        $current_user->Authenticate($user->username, $new_pass);
    }

    return true;
}
