<?php

if (! defined('mnmpath')) {
    require_once __DIR__.'/../config.php';
    require_once mnminclude.'html1.php';
}

array_push($globals['cache-control'], 'no-cache');
http_cache();

$message = new PrivateMessage;

if (!empty($_POST['author'])) {
    $message_id = intval($_REQUEST['id']);

    if ($message_id > 0) {
        save_post($message_id);
    } else {
        save_post(0);
    }
} else {
    if (!empty($_REQUEST['id'])) {
        if (($message = PrivateMessage::from_db(intval($_REQUEST['id'])))) {
            $message->print_edit_form();
        }
    } else {
        // A new post
        $message = new PrivateMessage;

        if (!empty($_REQUEST['user_id'])) {
            $message->to = intval($_REQUEST['user_id']);
        }

        $message->author = $current_user->user_id;
        $message->print_edit_form();
    }
}

function save_post($message_id)
{
    global $link, $db, $message, $current_user, $globals, $site_key;

    if ($current_user->user_id != intval($_POST['author'])) {
        die;
    }

    $message = new PrivateMessage;

    $to_user = User::get_valid_username($_POST['to_user']);

    if (! $to_user) {
        die('ERROR: ' . _('неправильное имя пользователя'));
    }

    $to = User::get_user_id($to_user);

    if (! $to > 0) {
        die('ERROR: ' . _('неправильный пользователь'));
    }

    if (! PrivateMessage::can_send($current_user->user_id, $to)) {
        die('ERROR: ' . _('получатель не имеет друга'));
    }

    $_POST['post'] = clean_text_with_tags($_POST['post'], 0, false, $globals['posts_len']);

    if (mb_strlen($_POST['post']) < 2) {
        die('ERROR: ' . _('очень короткий текст'));
    }

    if (!empty($_FILES['image']['tmp_name'])) {
        if ($limit_exceded = Upload::current_user_limit_exceded($_FILES['image']['size'])) {
            die('ERROR: ' . $limit_exceded);
        }
    }

    // Check the post wasn't already stored
    $message->randkey = intval($_POST['key']);
    $message->author = $current_user->user_id;
    $message->to = $to;
    $message->content = $_POST['post'];

    $db->transaction();

    $dupe = intval($db->get_var("select count(*) from privates where user = $current_user->user_id and date > date_sub(now(), interval 5 minute) and randkey = $message->randkey FOR UPDATE"));

    if ($dupe) {
        $db->rollback();

        die('ERROR: ' . _('предварительно записанное сообщение'));
    }

    // Verify that there are a period of 1 minute between posts.
    if (intval($db->get_var("select count(*) from privates where user= $current_user->user_id and date > date_sub(now(), interval 15 second)"))> 0) {
        $db->rollback();

        die('ERROR: ' . _('вы должны ждать 15 секунд между сообщениями'));
    }

    // Verify that there less than X messages from the same user in a day
    if (intval($db->get_var("select count(*) from privates where user= $current_user->user_id and date > date_sub(now(), interval 1 day)"))> 160) {
        $db->rollback();

        die('ERROR: ' . _('слишком много сообщений за один день'));
    }

    $db->commit();
    $message->store();

    notify_user($current_user->user_id, $to, $message->content);

    User::add_notification($message->to, 'private');

    // Check image upload or delete
    if ($_POST['image_delete']) {
        $message->delete_image();
    } else {
        $message->store_image_from_form('image');
    }

    $message = PrivateMessage::from_db($message->id); // Reread the object
    $message->print_summary();
}

function notify_user($from, $to, $text)
{
    global $globals;

    $sender = new User($from);
    $user = new User($to);

    if (!$user || !$sender || !check_email($user->email) || !User::get_pref($to, 'notify_priv')) {
        return;
    }

    require_once(mnminclude.'mail.php');

    $url = $globals['scheme'].'//'.get_server_name().$user->get_uri('notes_privates');
    $subject = "Уведомление о личном сообщении от $sender->username";
    $message = "$sender->username " . _('написала') . ":\n$url\n\n$text";

    send_mail($user->email, $subject, $message);
}
