<?php
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//      http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".
// Modification of sugata.ru, 2019

if (is_file(mnminclude . 'ads-credits-functions.php')) {
    require_once mnminclude . 'ads-credits-functions.php';
}

// Warning, it redirects to the content of the variable
if (!empty($globals['lounge'])) {
    die(header('Location: http://' . get_server_name() . $globals['base_url_general'] . $globals['lounge']));
}

if (PHP_SAPI !== 'cli' && !empty($globals['force_ssl']) && !$globals['https'] && !isset($_GET['force'])) {
    header('HTTP/1.1 301 Moved');
    die(header('Location: https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']));
}

$globals['extra_js'] = array();
$globals['extra_css'] = array();
$globals['extra_css_after'] = array();

$globals['extra_vendor_js'] = array();
$globals['extra_vendor_css'] = array();

if (!$globals['bot'] && ($globals['allow_partial'] || preg_match('/sugata/i', $_SERVER['HTTP_USER_AGENT']))) {
    if (!$globals['mobile']) {
        $globals['ads'] = false;
    }

    if (isset($_REQUEST['partial'])) {
        $globals['partial'] = true;
        $_SERVER['QUERY_STRING'] = preg_replace('/partial&|\?partial$|&partial/', '', $_SERVER['QUERY_STRING']);
    } else {
        $globals['partial'] = false;
    }
}

class MenuOption
{
    // Small helper class to store links' information
    public function __construct($text, $url, $active = false, $title = '', $class = '')
    {
        $this->text = $text;
        $this->url = $url;
        $this->title = $title;
        $this->selected = ($active && ($active == $this->text));
        $this->class = $class;
    }
}

function do_tabs($tab_name, $tab_selected = false, $extra_tab = false)
{
    /* Not used any more */
}


	  function rus($type,$num){
	$strlen_num = strlen($num);
	
	if($num <= 21){
		$numres = $num;
	} elseif($strlen_num == 2){
		$parsnum = substr($num,1,2);
		$numres = str_replace('0','10',$parsnum);
	} elseif($strlen_num == 3){
		$parsnum = substr($num,2,3);
		$numres = str_replace('0','10',$parsnum);
	} elseif($strlen_num == 4){
		$parsnum = substr($num,3,4);
		$numres = str_replace('0','10',$parsnum);
	} elseif($strlen_num == 5){
		$parsnum = substr($num,4,5);
		$numres = str_replace('0','10',$parsnum);
	}

	if($type == 'comm'){
		if($numres == 1){
			$gram_num_record = 'комментарий';
		} elseif($numres == 0){
			$gram_num_record = 'постов';
		} elseif($numres < 5){
			$gram_num_record = 'комментария';
		} elseif($numres < 21){
			$gram_num_record = 'комментариев';
		}  elseif($numres == 21){
			$gram_num_record = 'комментарий';
		}
	}
	
	if($type == 'vots'){
		if($numres == 1){
			$gram_num_record = 'голос';
		} elseif($numres == 0){
			$gram_num_record = 'голосов';
		} elseif($numres < 5){
			$gram_num_record = 'голоса';
		} elseif($numres < 21){
			$gram_num_record = 'голосов';
		}  elseif($numres == 21){
			$gram_num_record = 'голос';
		}
	}
	
	if($type == 'clic'){
		if($numres == 1){
			$gram_num_record = 'клик';
		} elseif($numres == 0){
			$gram_num_record = 'кликов';
		} elseif($numres < 5){
			$gram_num_record = 'клика';
		} elseif($numres < 21){
			$gram_num_record = 'кликов';
		}  elseif($numres == 21){
			$gram_num_record = 'клик';
		}
	}
	
	
		return $gram_num_record;
}

function do_header($title, $id = 'home', $options = false, $tab_options = false, $tab_class = 'dropdown-menu menu-subheader')
{

    global $current_user, $dblang, $globals, $db;

    header('Content-Type: text/html; charset=utf-8');

    // Security headers
    header('X-Frame-Options: SAMEORIGIN');
    header('X-UA-Compatible: IE=edge');

    if ($globals['force_ssl'] && $globals['https']) {
        header('Strict-Transport-Security: max-age=15638400'); // 181 days, ssllabs doesn't like less than 180
    }

    http_cache();

    $globals['security_key'] = get_security_key();

    setcookie('k', $globals['security_key'], 0, $globals['base_url_general']);

    if (!empty($_REQUEST['q'])) {
        $globals['q'] = $_REQUEST['q'];
    }

    if ($current_user->user_id > 0) {
        $globals['extra_js'][] = 'jquery.form.min.js';
    }

    $this_site = SitesMgr::get_info();

//	print_r(SitesMgr::get_info());
	//exit;
    $this_site_properties = SitesMgr::get_extended_properties();

    if ($this_site->sub) {
        $this_site->url = $this_site->base_url . 's/' . $this_site->name;
    } else {
         $this_site->url = $this_site->base_url;
    }

    // Check if the sub has a logo and calculate the width
    if ($this_site->media_id > 0 && $this_site->media_dim1 > 0 && $this_site->media_dim2 > 0) {
        $r = $this_site->media_dim1 / $this_site->media_dim2;

        if ($globals['mobile']) {
            $this_site->logo_height = $globals['media_sublogo_height_mobile'];
        } else {
            $this_site->logo_height = $globals['media_sublogo_height'];
        }

        $this_site->logo_width = round($r * $this_site->logo_height);
        $this_site->logo_url = Upload::get_cache_relative_dir($this_site->id) . '/media_thumb-sub_logo-' . $this_site->id . '.' . $this_site->media_extension . '?' . $this_site->media_date;
    }

    if ($this_site->nsfw) {
        $globals['ads'] = false;
    }

    if (!empty($this_site_properties['post_html'])) {
        $globals['post_html'] = $this_site_properties['post_html'];
    }

    if ($this_site_properties['message']) {
        $this_site_properties['message_html'] = LCPBase::html($this_site_properties['message']);
    }

    if (!is_array($options)) {
        $left_options = array();
		
     //   print_r('<br>==<br>');
	//	print_r($this_site->enabled);
	//	print_r('<br>');
	//	print_r($this_site_properties['new_disabled']);
 
    if ($this_site->id == '4') {
          if ($globals['mobile']) {  } else{    $left_options[] = new MenuOption(_('Главная'), $globals['base_url'], $id, _('Главная страница'));}
            $left_options[] = new MenuOption(_('Новое'), $globals['base_url'] . 'queue', $id, _('новости в ожидании перевода в статус публикация'));
            $left_options[] = new MenuOption(_('статьи'), $globals['base_url'] . 'articles', $id, _('статьи'));
            $left_options[] = new MenuOption(_('Пространства'), $globals['base_url'] . 'subs', $id, _('Пространства'));
            $left_options[] = new MenuOption(_('S/+'), $globals['base_url'] . '?meta=_*', $id, _('С пространств'));
            $left_options[] = new MenuOption(_('Популярные'), $globals['base_url'] . 'popular', $id, _('новости в ожидании перевода в статус публикация'));
         //   $left_options[] = new MenuOption(_('Поиск'), $globals['base_url'] . 'search', $id, _('поиск по сайту'));
    } else {
        if ($globals['mobile']) {  } else{     $left_options[] = new MenuOption(_('Главная'), $globals['base_url'], $id, _('Главная страница'));}
            $left_options[] = new MenuOption(_('Новое'), $globals['base_url'] . 'queue', $id, _('новости в ожидании'));
            $left_options[] = new MenuOption(_('статьи'), $globals['base_url'] . 'articles', $id, _('статьи'));
            $left_options[] = new MenuOption(_('Популярные'), $globals['base_url'] . 'popular', $id, _('истории с наибольшим количеством голосов'));
        //    $left_options[] = new MenuOption(_('самые посещаемые'), $globals['base_url'] . 'top_visited', $id, _('самые посещаемые / читаемые истории'));
    }

        $right_options = array();
        $right_options[] = new MenuOption(_('Разговор'), $globals['base_url'] . 'sneak', $id, _('Разговор в реальном времени'));
        $right_options[] = new MenuOption(_('Заметки'), post_get_base_url(), $id, _('читать или писать заметки и личные сообщения'));
        $right_options[] = new MenuOption(_('галерея'), 'javascript:fancybox_gallery(\'all\');', false, _('изображения, загруженные пользователями'));
		//$right_options[] = new MenuOption(_('помощь'), $globals['base_url'] . 'help', $id, _('часто задаваемые вопросы'));
		
		
		if ($this_site->enabled && empty($this_site_properties['new_disabled'])) {
            $submit_new_post_text = boolval($globals['mobile']) ? _(' разместить') : _('<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 20010904//EN"
 "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
<svg class="add" version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="14px" height="14px" viewBox="0 0 1280.000000 1280.000000"
 preserveAspectRatio="xMidYMid meet">
<g transform="translate(0.000000,1280.000000) scale(0.100000,-0.100000)"
 stroke="none">
<path d="M5150 12613 c-45 -7 -134 -57 -176 -98 -43 -41 -84 -102 -100 -145
-5 -14 -11 -952 -14 -2225 l-5 -2200 -2200 -5 c-1256 -3 -2211 -9 -2225 -14
-80 -29 -171 -114 -215 -200 l-30 -59 0 -1271 0 -1271 28 -56 c16 -31 39 -67
51 -80 38 -40 85 -79 96 -79 5 0 10 -4 10 -8 0 -4 26 -17 58 -27 56 -20 103
-20 2245 -23 l2187 -2 0 -2188 c0 -2306 -2 -2206 44 -2291 23 -44 105 -131
122 -131 8 0 14 -4 14 -10 0 -5 5 -10 11 -10 6 0 25 -6 42 -13 86 -37 82 -37
1307 -37 880 0 1213 3 1239 12 73 23 121 43 121 51 0 4 6 7 13 7 20 0 102 90
127 140 12 25 28 54 34 65 8 15 12 612 14 2212 l2 2193 2188 3 c2049 2 2190 3
2230 20 54 22 120 65 149 97 39 43 43 49 43 60 0 5 5 10 10 10 6 0 10 6 10 13
0 6 8 26 18 42 16 28 17 111 17 1305 0 1194 -1 1277 -17 1305 -10 17 -18 36
-18 43 0 6 -4 12 -10 12 -5 0 -10 5 -10 11 0 29 -127 132 -190 155 -14 5 -967
11 -2220 14 l-2195 5 -5 2190 c-5 1927 -7 2194 -21 2225 -46 106 -105 174
-194 222 l-60 33 -1250 1 c-687 1 -1261 0 -1275 -3z"/>
</g>
</svg> разместить');
            $right_options[] = new MenuOption($submit_new_post_text, $globals['base_url'] . 'submit', $id, _('отправить новую историю'), "submit_new_post");
           // $left_options[] = new MenuOption(_('Создать статью'), $globals['base_url'] . 'submit?type=article&write=true', $id, _('отправить новую статью'), 'submit_new_article');
        }
		
		
    } else {
        $left_options = $options;
        $right_options = array();

        $right_options[] = new MenuOption(_('Новое'), $globals['base_url'] . 'queue', '', _('новости в ожидании утверждения'));
        $right_options[] = new MenuOption(_('Разговор'), $globals['base_url'] . 'sneak', $id, _('Разговор в реальном времени'));
        $right_options[] = new MenuOption(_('Заметки'), post_get_base_url(), $id, _('читать или писать заметки и личные сообщения'));
        $right_options[] = new MenuOption(_('галерея'), 'javascript:fancybox_gallery(\'all\');', false, _('изображения, загруженные пользователями'));
    }

    $tabs = Tabs::renderForSection($id, $tab_options, $tab_class);

    $followed_subs = array();

    if ($globals['mobile'] && $current_user->user_id > 0) {
        $subs = SitesMgr::get_subscriptions($current_user->user_id);

        foreach ($subs as $sub) {
            if (!$sub->enabled) {
                continue;
            }

            $sub->site_info = SitesMgr::get_info($sub->id);

            // Check if the sub has a logo and calculate the width
            if ($sub->site_info->media_id > 0 && $sub->site_info->media_dim1 > 0 && $sub->site_info->media_dim2 > 0) {
                $r = $sub->site_info->media_dim1 / $sub->site_info->media_dim2;

                if ($globals['mobile']) {
                    $sub->site_info->logo_height = $globals['media_sublogo_height_mobile'];
                } else {
                    $sub->site_info->logo_height = $globals['media_sublogo_height'];
                }

                $sub->site_info->logo_width = round($r * $sub->site_info->logo_height);
                $sub->site_info->logo_url = Upload::get_cache_relative_dir($sub->site_info->id) . '/media_thumb-sub_logo-' . $sub->site_info->id . '.' . $sub->site_info->media_extension . '?' . $sub->site_info->media_date;
            }

            $followed_subs[] = $sub;
        }
    }

    $sites = $db->get_results('SELECT * FROM subs WHERE visible ORDER BY id ASC;');

    return Haanga::Load('header.html', compact(
        'title', 'greeting', 'id', 'left_options', 'right_options',
        'sites', 'this_site', 'this_site_properties', 'tabs', 'followed_subs'
    ));
}

function do_js_from_array($array)
{
    global $globals;

    foreach ($array as $js) {
        if (preg_match('/^http|^\//', $js)) {
            echo '<script src="' . $js . '"></script>' . "\n";
        } elseif (preg_match('/\.js$/', $js)) {
            echo '<script src="' . $globals['base_static'] . 'js/' . $js . '"></script>' . "\n";
        } else {
            echo '<script src="' . $globals['base_url'] . 'js/' . $js . '"></script>' . "\n";
        }
    }
}

function do_footer($credits = true)
{
    return Haanga::Load('footer.html');
}

function do_footer_menu()
{
    return Haanga::Load('footer_menu.html');
}

function do_rss_box($search_rss = 'rss')
{
    global $globals, $current_user;

    if (!$globals['mobile']) {
        return Haanga::Load('rss_box.html', compact('search_rss'));
    }
}

function force_authentication()
{
    global $current_user, $globals;

    if (!$current_user->authenticated) {
        die(header('Location: https://sugata.ru/login'));
    }

    return true;
}

function mobile_redirect()
{
    global $globals;

    if (
        $globals['mobile']
        && empty($_COOKIE['nomobile'])
        && !preg_match('/(pad|tablet|wii|tv)\W/i', $_SERVER['HTTP_USER_AGENT'])
        && $globals['url_shortener_mobile_to']
        && (
            !$_SERVER['HTTP_REFERER']
            // Check if the user comes from our own domain
            // If so, don't redirect her
             || !preg_match('/^https*:\/\/.*?' . preg_quote(preg_replace('/.+?\.(.+?\..+?)$/', "$1", get_server_name())) . '/i', $_SERVER['HTTP_REFERER'])
        )
    ) {
        die(header('Location: http://' . $globals['url_shortener_mobile_to'] . $_SERVER['REQUEST_URI']));
    }
}

function do_pages_reverse($total, $page_size = 25, $margin = true)
{
    global $db, $globals;

    if ($total > 0 && $total < $page_size) {
        return;
    }

    if ($globals['mobile']) {
        $index_limit = 1;
        $go_prev = '';
        $go_next = '';
    } else {
        $index_limit = 5;
        $go_prev = _('назад');
        $go_next = _('далее');
    }

    $separator = '&hellip;';

    $query = preg_replace('/page=[0-9]+/', '', $_SERVER['QUERY_STRING']);
    $query = preg_replace('/^&*(.*)&*$/', "$1", $query);

    if (!empty($query)) {
        $query = '&amp;' . htmlspecialchars($query);
    }

    $total_pages = ceil($total / $page_size);
    $current = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : $total_pages;
    $start = max($current - intval($index_limit / 2), 1);
    $end = $start + $index_limit - 1;

    if ($margin) {
        echo '<div class="pages margin">';
    } else {
        echo '<div class="pages">';
    }

    if ($total < 0 || ($current < $total_pages)) {
        $i = $current + 1;
        $nofollow = ($i > 10) ? ' rel="nofollow"' : '';

        echo '<a href="?page=' . $i . $query . '"' . $nofollow . ' rel="next">' . $go_next . ' &#171;</a>';
    } else {
        echo '<span class="nextprev">&#171; ' . $go_next . '</span>';
    }

    if ($total_pages > 0) {
        if ($total_pages > $end) {
            $i = $total_pages;
            $nofollow = ($i > 10) ? ' rel="nofollow"' : '';

            echo '<a href="?page=' . $i . $query . '" title="' . _('перейти на страницу') . " $i" . '"' . $nofollow . '>' . $i . '</a>';
            echo "<span>$separator</span>";
        }

        for ($i = min($end, $total_pages); $i >= $start; $i--) {
            if ($i == $current) {
                echo '<span class="current">' . $i . '</span>';
            } else {
                $nofollow = ($i > 10) ? ' rel="nofollow"' : '';

                echo '<a href="?page=' . $i . $query . '" title="' . _('перейти на страницу') . " $i" . '"' . $nofollow . '>' . $i . '</a>';
            }
        }

        if ($start > 1) {
            $i = 1;

            echo "<span>$separator</span>";
            echo '<a href="?page=' . $i . $query . '" title="' . _('перейти на страницу') . " $i" . '">' . $i . '</a>';
        }
    } else {
        echo '<span class="current">' . $current . '</span>';

        if ($current > 2) {
            echo "<span>$separator</span>";
            echo '<a href="?page=1' . $query . '" title="' . _('перейти на страницу') . " 1" . '">1</a>';
        }
    }

    if ($current == 1) {
        echo '<span class="nextprev">&#187; ' . $go_prev . '</span>';
    } else {
        $i = $current - 1;
        $nofollow = ($i > 10) ? ' rel="nofollow"' : '';

        echo '<a href="?page=' . $i . $query . '"' . $nofollow . ' rel="prev">&#187; ' . $go_prev . '</a>';
    }

    echo '</div>';
}

function do_pages($total, $page_size = 25, $margin = true)
{
    global $db, $globals;

    if ($total > 0 && $total < $page_size) {
        return;
    }

    if (!$globals['mobile']) {
        $index_limit = 5;
        $go_prev = _('назад');
        $go_next = _('далее');
    } else {
        $index_limit = 1;
        $go_prev = '';
        $go_next = '';
    }

    $separator = '&hellip;';

    $query = preg_replace('/page=[0-9]+/', '', $_SERVER['QUERY_STRING']);
    $query = preg_replace('/^&*(.*)&*$/', "$1", $query);

    if (!empty($query)) {
        $query = '&amp;' . htmlspecialchars($query);
    }

    $current = get_current_page();
    $total_pages = ceil($total / $page_size);
    $start = max($current - intval($index_limit / 2), 1);
    $end = $start + $index_limit - 1;

    if ($margin) {
        echo '<div class="pages margin">';
    } else {
        echo '<div class="pages">';
    }

    if ($current == 1) {
        echo '<span class="nextprev">&#171; ' . $go_prev . '</span>';
    } else {
        $i = $current - 1;
        $nofollow = ($i > 10) ? ' rel="nofollow"' : '';

        echo '<a href="?page=' . $i . $query . '"' . $nofollow . ' rel="prev">&#171; ' . $go_prev . '</a>';
    }

    if ($total_pages > 0) {
        if ($start > 1) {
            $i = 1;

            echo '<a href="?page=' . $i . $query . '" title="' . _('перейти на страницу') . " $i" . '">' . $i . '</a>';
            echo "<span>$separator</span>";
        }

        for ($i = $start; $i <= $end && $i <= $total_pages; $i++) {
            if ($i == $current) {
                echo '<span class="current">' . $i . '</span>';
            } else {
                $nofollow = ($i > 10) ? ' rel="nofollow"' : '';
                echo '<a href="?page=' . $i . $query . '" title="' . _('перейти на страницу') . " $i" . '"' . $nofollow . '>' . $i . '</a>';
            }
        }

        if ($total_pages > $end) {
            $i = $total_pages;
            $nofollow = ($i > 10) ? ' rel="nofollow"' : '';

            echo "<span>$separator</span>";
            echo '<a href="?page=' . $i . $query . '" title="' . _('перейти на страницу') . " $i" . '"' . $nofollow . '>' . $i . '</a>';
        }
    } else {
        if ($current > 2) {
            echo '<a href="?page=1' . $query . '" title="' . _('перейти на страницу') . " 1" . '">1</a>';
            echo "<span>$separator</span>";
        }

        echo '<span class="current">' . $current . '</span>';
    }

    if ($total < 0 || $current < $total_pages) {
        $i = $current + 1;
        $nofollow = ($i > 10) ? ' rel="nofollow"' : '';

        echo '<a href="?page=' . $i . $query . '"' . $nofollow . ' rel="next">' . $go_next . ' &#187;</a>';
    } else {
        echo '<span class="nextprev">&#187; ' . $go_next . '</span>';
    }

    echo '</div>';
}

function get_subs_main()
{
    global $globals;
 
 
 
    if (!empty($globals['submnm'])) {
        return array();
    }

    return SitesMgr::get_sub_subs();
}

function get_subs_subscriptions(array $main = array())
{
    global $globals, $current_user;

    $ids = array_map(function($value) {
        return $value->id;
    }, $main);

    $subs = array();

    foreach (SitesMgr::get_subscriptions($current_user->user_id) as $sub) {
        if (!in_array($sub->id, $ids) && SitesMgr::can_send($sub->id)) {
            $subs[] = $sub;
        }
    }

    return $subs;
}

function get_sub_selected($selected)
{
    global $globals, $current_user;

    $selected = $selected ?: SitesMgr::my_id();

    if ((SitesMgr::can_send($selected) || $current_user->user_level === 'god' ) && ($sub = SitesMgr::get_info($selected))) {
        return $sub;
    }
}

//Used in editlink.php and submit.php
/* function print_subs_form($selected = false)
{
    $subs = get_subs_main();
	
	print_r(get_subs_main());
	
    $subscriptions = get_subs_subscriptions($subs);

    if ($selected = get_sub_selected($selected)) {
        $selected = $selected->id;
    }

    return Haanga::Load('form_subs.html', compact('selected', 'subs', 'subscriptions'));
} */

//Used in editlink.php and submit.php
//Used in editlink.php and submit.php
function print_subs_form($selected = false)
{
    global $db, $globals, $current_user;
    function id($s)
    {
        return $s->id;
    }
    if (!empty($globals['submnm'])) {
        $subs = false;
    } else {
        $subs = SitesMgr::get_sub_subs();
        $ids = array_map('id', $subs);
        // A link in a sub is edited from another sub, or from the main site
        // Add its selected sub.
        if ($selected && !in_array($selected, $ids) && SitesMgr::can_send($selected)) {
            $e = SitesMgr::get_info($selected);
            if ($e) {
                array_unshift($subs, $e); // Add to the form
                array_unshift($ids, $selected); // Avoid to show it again if subscribed to
            }
        }
        $extras = SitesMgr::get_subscriptions($current_user->user_id);
        $subscriptions = array();
        foreach ($extras as $s) {
            if (!in_array($s->id, $ids) && SitesMgr::can_send($s->id)) {
                $subscriptions[] = $s;
            }
        }
    }
    if ($selected == false) {
        $selected = SitesMgr::my_id();
    }
    return Haanga::Load('form_subs.html', compact('selected', 'subs', 'subscriptions'));
}


function do_vertical_tags($what = false)
{
    global $db, $globals, $dblang;

    if ($globals['mobile'] || $globals['submnm']) {
        return;
    }

    if (empty($what)) {
        $status = "!= 'discarded'";
    } else {
        $status = '= "' . $what . '"';
    }

    $cache_key = 'tags_' . $globals['site_shortname'] . $globals['v'] . $status;

    if (memcache_mprint($cache_key)) {
        return;
    }

    echo '<!-- Расчет ' . __FUNCTION__ . ' -->';

    $min_date = date("Y-m-d H:i:00", $globals['now'] - 172800); // 48 hours (edit! 2zero)
    $from_where = "FROM links, sub_statuses WHERE id = " . SitesMgr::my_id() . " AND link_id = link and link_date > '$min_date' and link_status $status";

    $res = $db->get_col("select link_tags $from_where");

    if (!$res || (count($res) <= 5)) {
        memcache_madd($cache_key, ' ', 900);
        return;
    }

    $min_pts = 8;
    $max_pts = 22;
    $max = 3;

    foreach ($res as $line) {
        foreach (explode(',', mb_strtolower($line)) as $w) {
            $w = trim($w);
            $words[$w]++;

            if ($words[$w] > $max) {
                $max = $words[$w];
            }
        }
    }

    $coef = ($max_pts - $min_pts) / ($max - 1);
    $words = array_slice($words, 0, 20);

    arsort($words);
    ksort($words);

    $content = '';

    foreach ($words as $word => $count) {
        $size = round($min_pts + ($count - 1) * $coef, 1);
        $op = round(0.4 + 0.6 * $count / $max, 2);

        $content .= '<a style="font-size: ' . $size . 'pt;opacity:' . $op . '" href="';

        if ($globals['base_search_url']) {
            $content .= $globals['base_url'] . $globals['base_search_url'] . 'tag:';
        } else {
            $content .= $globals['base_url'] . 'search?p=tags&amp;q=';
        }

        $content .= urlencode($word) . '">' . $word . '</a>  ';
    }

    if ($max > 2) {
        $title = _('теги');
        $url = $globals['base_url'] . 'cloud';

        echo $output = Haanga::Load('tags_sidebox.html', compact('content', 'title', 'url'), true);
    } else {
        $output = ' ';
    }

    memcache_madd($cache_key, $output, 900);
}




/*
function do_categories_cloud($what=false, $hours = 488) {
	global $db, $globals, $dblang;
	if ($globals['mobile']) return;
	$cache_key = 'categories_cloud_'.$globals['css_main'].$what;
	if(memcache_mprint($cache_key)) return;
	if (!empty($what)) {
		$status = '= "'.$what. '"';
	} else {
		$status = "!= 'discarded'";
	}
	$min_pts = 8;
	$max_pts = 22;
	$min_date = date("Y-m-d H:i:00", $globals['now'] - $hours*3600);
	$from_where = "from categories, links where link_status $status and link_date > '$min_date' and link_category = category_id group by category_name";
	$max = 0;
	$res = $db->get_results("select count(*) as count, lower(category_name) as category_name, category_id $from_where order by count desc limit 10");
	if ($res) {
		if ($what == 'queued') $page = $globals['base_url'].'shakeit.php?category=';
		else  $page = $globals['base_url'].'?category=';
		$title = _('categorías populares');
		$counts = array();
		$names = array();
		foreach ($res as $item) {
			if ($item->count > 1) {
				if ($item->count > $max) $max = $item->count;
				$counts[$item->category_id] = $item->count;
				$names[$item->category_name] = $item->category_id;
			}
		}
		ksort($names);
		$coef = (($max - 1) > 0)?(($max_pts - $min_pts)/($max-1)):0;
		foreach ($names as $name => $id) {
			$count = $counts[$id];
			$size = round($min_pts + ($count-1)*$coef, 1);
			$op = round(0.3 + 0.7*$count/$max, 2);
			$content .= '<a style="font-size: '.$size.'pt;opacity:'.$op.'" href="'.$page.$id.'">'.$name.'</a> ';
		}
		$vars = compact('content', 'title', 'url');
		$output = Haanga::Load('tags_sidebox.html', $vars, true);
		echo $output;
		memcache_madd($cache_key, $output, 600);
	}
}

*/

function do_best_sites()
{
    global $db, $globals, $dblang;

    if ($globals['mobile']) {
        return;
    }

    $key = 'best_sites_' . $globals['site_shortname'] . $globals['v'];

    if (memcache_mprint($key)) {
        return;
    }

    echo '<!-- Расчет ' . __FUNCTION__ . ' -->';

    $min_date = date("Y-m-d H:i:00", $globals['now'] - 172800); // about  48 hours

    // The order is not exactly the votes counts
    // but a time-decreasing function applied to the number of votes
    $res = $db->get_results("select sum(link_votes + link_anonymous) as total_count, sum(link_votes-link_negatives*2)*(1-(unix_timestamp(now())-unix_timestamp(link_date))*0.8/172800) as coef, sum(link_votes-link_negatives*2) as total, blog_url from links, blogs, sub_statuses where id = " . SitesMgr::my_id() . " AND link_id = link AND date > '$min_date' and status='published' and link_blog = blog_id group by link_blog order by coef desc limit 10");

    if ($res && count($res) > 4) {
        $title = _('sitios más votados');

        echo $output = Haanga::Load('best_sites_posts.html', compact('res', 'title'), true);
    } else {
        $output = ' ';
    }

    memcache_madd($key, $output, 300);
}

function do_most_clicked_sites()
{
    global $db, $globals, $dblang;

    if ($globals['mobile']) {
        return;
    }

    $key = 'most_clicked_sites_' . $globals['site_shortname'] . $globals['v'];

    if (memcache_mprint($key)) {
        return;
    }

    echo '<!-- Расчет ' . __FUNCTION__ . ' -->';

    $min_date = date("Y-m-d H:i:00", $globals['now'] - 172800); // about  48 hours

    // The order is not exactly the votes counts
    // but a time-decreasing function applied to the number of votes
    $res = $db->get_results("select sum(counter) as total_count, sum(counter*(1-(unix_timestamp(now())-unix_timestamp(link_date))*0.5/172800)) as value, blog_url from links, link_clicks, blogs, sub_statuses where sub_statuses.id = " . SitesMgr::my_id() . " AND link_id = link AND date > '$min_date' and status='published' and link_blog = blog_id AND link_clicks.id = link group by link_blog order by value desc limit 10");

    if ($res && count($res) > 4) {
        $title = _('посещаемые сайты');

        echo $output = Haanga::Load('best_sites_posts.html', compact('res', 'title'), true);
    } else {
        $output = ' '; // Use a space to be sure it's memcached.
    }

    memcache_madd($key, $output, 300);
}

function do_best_comments()
{
    global $db, $globals, $dblang;

    if ($globals['mobile'] || $globals['bot']) {
        return;
    }

    $key = 'best_comments_' . $globals['site_shortname'] . $globals['v'];

    if (memcache_mprint($key)) {
        return;
    }

    echo '<!-- Расчет ' . __FUNCTION__ . ' -->';

    $min_date = date("Y-m-d H:i:00", $globals['now'] - 50000); // about 12 hours
    $link_min_date = date("Y-m-d H:i:00", $globals['now'] - 86400 * 2); // 48 hours
    $now = intval($globals['now'] / 60) * 60;

    // The order is not exactly the comment_karma
    // but a time-decreasing function applied to the number of votes
    $res = $db->get_results(DbHelper::queryPlain('
        SELECT `comment_id`, `comment_order`, `user_id`, `user_login`, `user_avatar`,
            `link_id`, `link_uri`, `link_title`, `link_comments`, `link_negatives` / `link_votes` AS `rel`,
            `comment_karma` * ( 1 - (' . $now . ' - UNIX_TIMESTAMP(`comment_date`)) * 0.7 / 43000) AS `value`
        FROM (`comments`, `links`, `users`, `sub_statuses`)
        WHERE (
            `id` = "' . SitesMgr::my_id() . '"
            AND `status` in ("published", "queued")
            AND `link_id` = link
            AND `date` > "' . $link_min_date . '"
            AND `comment_date` > "' . $min_date . '"
            AND LENGTH(`comment_content`) > 32
            AND `link_negatives` / `link_votes` < 0.5
            AND `comment_karma` > 50
            AND `comment_link_id` = `link`
            AND `comment_user_id` = `user_id`
            AND `user_level` != "disabled"
        )
        ORDER BY `value` DESC
        LIMIT 10;
    '));

    if (!$res || (count($res) <= 4)) {
        memcache_madd($key, ' ', 300);
        return;
    }

    $foo = new Comment();
    $objects = array();

    foreach ($res as $comment) {
        $obj = new stdClass();

        $obj->id = $foo->id = $comment->comment_id;
        $obj->link = $foo->get_relative_individual_permalink();
        $obj->user_id = $comment->user_id;
        $obj->avatar = $comment->user_avatar;
        $obj->title = $comment->link_title;
        $obj->username = $comment->user_login;
        $obj->tooltip = 'c';

        $objects[] = $obj;
    }

    $title = _('лучшие комментарии');
    $url = $globals['base_url'] . 'top_comments';

    echo $output = Haanga::Load('best_comments_posts.html', compact('objects', 'title', 'url'), true);

    memcache_madd($key, $output, 300);
}

function do_best_story_comments($link)
{
    global $db, $globals, $dblang;

    if ($globals['mobile']) {
        return;
    }

    if ($link->comments > 30 && $globals['now'] - $link->date < 86400 * 4) {
        $do_cache = true;
        $sql_cache = 'SQL_NO_CACHE';
    } else {
        $do_cache = false;
        $sql_cache = 'SQL_CACHE';
    }

    $key = 'best_story_comments_' . $globals['v'] . '_' . $link->id;

    if ($do_cache && memcache_mprint($key)) {
        return;
    }

    echo '<!-- Расчет ' . __FUNCTION__ . ' -->';

    $limit = min(15, intval($link->comments / 5));
    $res = $db->get_results("select $sql_cache comment_id, comment_order, user_id, user_login, user_avatar, comment_content as content from comments, users  where comment_link_id = $link->id and comment_karma > 30 and comment_user_id = user_id order by comment_karma desc limit $limit");

    if (!$res || (count($res) <= 4)) {
        if ($do_cache) {
            memcache_madd($key, ' ', 300);
        }

        return;
    }

    $objects = array();

    foreach ($res as $comment) {
        $obj = new stdClass();
        $obj->id = $comment->comment_id;
        $obj->link = $link->get_relative_permalink() . '/000' . $comment->comment_order;
        $obj->user_id = $comment->user_id;
        $obj->avatar = $comment->user_avatar;
        $obj->title = text_to_summary($comment->content, 75);
        $obj->username = $comment->user_login;
        $obj->tooltip = 'c';

        $objects[] = $obj;
    }

    $title = _('лучшие комментарии');
    $url = $link->get_relative_permalink() . '/best-comments';

    echo $output = Haanga::Load('best_comments_posts.html', compact('objects', 'title', 'url'), true);

    if ($do_cache) {
        memcache_madd($key, $output, 300);
    }
}

function do_my_menu()
{
    global $db, $globals, $current_user;
    if ($globals['mobile']) {
        return;
    }
    $my_id = $current_user->user_id;
    $count = (int) $db->get_var('SELECT SQL_CACHE COUNT(*) FROM friends, users WHERE ( friend_type = "manual" AND friend_from = "'.$my_id.'" AND user_id = friend_to AND friend_value > 0 );');
    echo $output = Haanga::Load('my_menu.html', compact('count'), true);

     if ($do_cache) {
        memcache_madd($key, $count, 300);
    }

}


function do_active_stories()
{
    global $db, $globals, $dblang;

    if ($globals['mobile']) {
        return;
    }

    $key = 'active_stories_' . $globals['site_shortname'] . $globals['v'];

    if (memcache_mprint($key)) {
        return;
    }

    echo '<!-- Расчет ' . __FUNCTION__ . ' -->';

    $top = new Annotation('top-actives-' . $globals['site_shortname']);

    if (!$top->read() || !($ids = array_filter(explode(',', $top->text)))) {
        return;
    }

    $links = array();
    $ids = array_slice($ids, 0, 5);

    foreach ($ids as $id) {
        $link = Link::from_db($id);

        if (!$link) {
            continue;
        }

        $link->url = $link->get_relative_permalink();
        $link->thumb = $link->has_thumb();
        $link->total_votes = $link->votes + $link->anonymous;

        if ($link->thumb) {
            $link->thumb_x = round($link->thumb_x / 2);
            $link->thumb_y = round($link->thumb_y / 2);
        }

        $link->check_warn();
        $links[] = $link;
    }

    $title = _('выпадающийся');
    $url = $globals['base_url'] . 'top_active';
    $subclass = 'red';

    echo $output = Haanga::Load('best_stories.html', compact('links', 'title', 'url', 'subclass'), true);

    memcache_madd($key, $output, 60);
}

function do_best_stories()
{
    global $db, $globals, $dblang;

    if ($globals['mobile']) {
        return;
    }

    $key = 'best_stories_' . $globals['site_shortname'] . $globals['v'];

    if (memcache_mprint($key)) {
        return;
    }

    echo '<!-- Расчет ' . __FUNCTION__ . ' -->';

    $min_date = date("Y-m-d H:i:00", $globals['now'] - 129600); // 36 hours

    // The order is not exactly the votes
    // but a time-decreasing function applied to the number of votes
    $res = $db->get_results("select link_id, (link_votes-link_negatives*2)*(1-(unix_timestamp(now())-unix_timestamp(link_date))*0.8/129600) as value from links, sub_statuses where id = " . SitesMgr::my_id() . " AND link_id = link AND status='published' and date > '$min_date' order by value desc limit 5");

    if (!$res || (count($res) <= 4)) {
        memcache_madd($key, ' ', 180);
        return;
    }

    $links = array();

    foreach ($res as $l) {
        $link = Link::from_db($l->link_id);

        $link->url = $link->get_relative_permalink();
        $link->thumb = $link->has_thumb();
        $link->total_votes = $link->votes + $link->anonymous;
        $link->check_warn();

        if ($link->thumb) {
            $link->thumb_x = round($link->thumb_x / 2);
            $link->thumb_y = round($link->thumb_y / 2);
        }

        $links[] = $link;
    }

    $title = _('популярные');
    $url = $globals['base_url'] . 'popular';
    $subclass = 'red';

    echo $output = Haanga::Load('best_stories_top.html', compact('links', 'title', 'url', 'subclass'), true);

    memcache_madd($key, $output, 180);
}

function do_best_queued()
{
    global $db, $globals, $dblang;

    if ($globals['mobile']) {
        return;
    }

    $key = 'best_queued_' . $globals['site_shortname'] . $globals['v'];

    if (memcache_mprint($key)) {
        return;
    }

    $avg_karma = intval($db->get_var("SELECT avg(karma) from sub_statuses WHERE id = " . SitesMgr::my_id() . " AND date >= date_sub(now(), interval 1 day) and status='published'"));
    $min_karma = intval($avg_karma / 4);
    $warned_threshold = intval($min_karma * 1.5);

    $min_date = date("Y-m-d H:i:00", $globals['now'] - 86400 * 3); // 3 days

    // The order is not exactly the votes
    // but a time-decreasing function applied to the number of votes
    $res = $db->get_results("select link_id from links, sub_statuses where id = " . SitesMgr::my_id() . " AND status='queued' and link_id = link AND link_karma > $min_karma AND date > '$min_date' order by link_karma desc limit 20");

    if (!$res || (count($res) <= 5)) {
        memcache_madd($key, ' ', 120);
        return;
    }

    $links = array();

    foreach ($res as $l) {
        $link = Link::from_db($l->link_id);

        if ($link->negatives > $link->votes / 10 && $link->karma < $warned_threshold) {
            continue;
        }

        if ($link->clicks / ($link->votes + $link->negatives) < 1.75) {
            continue;
        }

        $link->url = $link->get_relative_permalink();
        $link->thumb = $link->has_thumb();
        $link->total_votes = $link->votes + $link->anonymous;
        $link->check_warn();

        if ($link->thumb) {
            $link->thumb_x = round($link->thumb_x / 2);
            $link->thumb_y = round($link->thumb_y / 2);
        }

        $links[] = $link;
    }

    $title = _('кандидаты');
    $url = $globals['base_url'] . 'queue?meta=_popular';
    $subclass = '';

    echo $output = Haanga::Load('best_stories.html', compact('links', 'title', 'url', 'subclass'), true);

    memcache_madd($key, $output, 120);
}

function do_most_clicked_stories()
{
    global $db, $globals, $dblang;

    if ($globals['mobile']) {
        return;
    }

    $key = 'most_clicked_' . $globals['site_shortname'] . $globals['v'];

    if (memcache_mprint($key)) {
        return;
    }

    echo '<!-- Расчет ' . __FUNCTION__ . ' -->';

    $min_date = date("Y-m-d H:i:00", $globals['now'] - 172800); // 48 hours

    // The order is not exactly the votes
    // but a time-decreasing function applied to the number of votes
    $res = $db->get_results("select link_id, counter*(1-(unix_timestamp(now())-unix_timestamp(link_date))*0.5/172800) as value from links, link_clicks, sub_statuses where sub_statuses.id = " . SitesMgr::my_id() . " AND link_id = link AND status='published' and date > '$min_date' and link_clicks.id = link order by value desc limit 5");

    if (!$res || (count($res) <= 4)) {
        memcache_madd($key, ' ', 180);
        return;
    }

    $links = array();

    foreach ($res as $l) {
        $link = Link::from_db($l->link_id);
        $link->url = $link->get_relative_permalink();
        $link->thumb = $link->has_thumb();
        $link->total_votes = $link->votes + $link->anonymous;
        $link->check_warn();

        if ($link->thumb) {
            $link->thumb_x = round($link->thumb_x / 2);
            $link->thumb_y = round($link->thumb_y / 2);
        }

        $links[] = $link;
    }

    $title = _('самое посещаемое');
    $url = $globals['base_url'] . 'top_visited';

    echo $output = Haanga::Load('most_clicked_stories.html', compact('links', 'title', 'url'), true);

    memcache_madd($key, $output, 180);
}

function do_best_posts($with_poll = false)
{
    global $db, $globals, $dblang;

    if ($globals['mobile']) {
        return;
    }

    $key = 'best_posts_' . $globals['site_shortname'] . $globals['v'];

    if (memcache_mprint($key)) {
        return;
    }

    echo '<!-- Расчет ' . __FUNCTION__ . ' -->';

    // 24 hours to regular posts, 96 hours to posts with polls
    $min_date = date('Y-m-d H:i:00', $globals['now'] - 86400 * ($with_poll ? 4 : 1));

    $res = $db->get_results(DbHelper::queryPlain('
        SELECT `posts`.`post_id`
        FROM (`posts`, `users`)
        ' . ($with_poll ? ('JOIN `polls` ON (`polls`.`post_id` = `posts`.`post_id`)') : '') . '
        WHERE (
            `post_date` > "' . $min_date . '"
            AND `post_user_id` = `user_id`
            AND `post_karma` > 0
        )
        ORDER BY `post_karma` DESC
        LIMIT 10;
    '));

    if (!$res || (count($res) <= 4)) {
        memcache_madd($key, ' ', 300);
        return;
    }

    $objects = array();

    foreach ($res as $p) {
        $post = new Post;
        $post->id = $p->post_id;
        $post->read();

        $obj = new stdClass();
        $obj->id = $post->id;
        $obj->link = post_get_base_url() . $post->id;
        $obj->user_id = $post->author;
        $obj->avatar = $post->avatar;
        $obj->title = text_to_summary($post->clean_content(), 80);
        $obj->username = $post->username;
        $obj->tooltip = 'p';

        $objects[] = $obj;
    }

    $title = _('Лучшие заметки');
    $url = post_get_base_url('_best');

    echo $output = Haanga::Load('best_comments_posts.html', compact('objects', 'title', 'url'), true);

    memcache_madd($key, $output, 300);
}

function do_last_blogs()
{
    global $db, $globals, $dblang;

    if ($globals['mobile'] || $globals['submnm']) {
        return;
    }

    $key = 'last_blogs_' . $globals['v'];

    if (memcache_mprint($key)) {
        return;
    }

    $entries = $db->get_results("select rss.blog_id, rss.user_id, title, url, user_login, user_avatar from rss, users where rss.user_id = users.user_id order by rss.date desc limit 10");

    if (empty($entries)) {
        memcache_madd($key, ' ', 300);
        return;
    }

    $objects = array();

    foreach ($entries as $entry) {
        $obj = new stdClass();

        $obj->user_id = $entry->user_id;
        $obj->avatar = $entry->user_avatar;
        $obj->title = text_to_summary($entry->title, 75);
        $obj->link = $entry->url;
        $obj->username = $entry->user_login;

        $objects[] = $obj;
    }

    $title = _('Сообщения в блогах');
    $url = $globals['base_url'] . 'rsss';

    echo $output = Haanga::Load('last_blogs.html', compact('objects', 'title', 'url'), true);

    memcache_madd($key, $output, 300);
}

function do_last_subs($status = 'published', $count = 10, $order = 'date')
{
    global $db, $globals, $dblang;

    if ($globals['mobile'] || $globals['submnm']) {
        return;
    }

    $key = "last_subs_$status-$count-$order_" . $globals['v'];

    if (memcache_mprint($key)) {
        return;
    }

    $where_not_banned = '';

    if (isset($globals['subs_banned']) and is_array($globals['subs_banned']) and count($globals['subs_banned']) > 0) {
        $where_not_banned = " and subs.name  not in ('" . implode(",", $globals['subs_banned']) . "')";
    }

    $ids = $db->get_col("select link from sub_statuses, subs, links where date > date_sub(now(), interval 48 hour) and status = '$status' and sub_statuses.id = origen and subs.id = sub_statuses.id and owner > 0 and not nsfw and link_id = link $where_not_banned order by $order desc limit $count");

    if (empty($ids)) {
        memcache_madd($key, ' ', 300);
        return;
    }

    $links = array();

    foreach ($ids as $id) {
        $link = Link::from_db($id);

        if (!$link) {
            continue;
        }

        $link->print_subname = true;
        $link->url = $link->get_permalink();
        //$link->thumb = $link->has_thumb();
        $link->total_votes = $link->votes + $link->anonymous;

         $sub_id = SitesMgr::get_info($link->sub_id);
         // Check if the sub has a logo and calculate the width
            if ($sub_id->media_id > 0 && $sub_id->media_dim1 > 0 && $sub_id->media_dim2 > 0) {
                $r = $sub_id->media_dim1 / $sub_id->media_dim2;
                $link->logo_width = round($r * $sub_id->logo_height);
                $link->logo_url = Upload::get_cache_relative_dir($sub_id->id) . '/media_thumb-sub_logo-' . $sub_id->id . '.' . $sub_id->media_extension . '?' . $sub_id->media_date;
            }

//        if ($link->thumb) {
  //          $link->thumb_x = round($link->thumb_x / 2);
    //        $link->thumb_y = round($link->thumb_y / 2);
      //  }

        $links[] = $link;
    }

    $title = _('В пространствах ');
    $url = $globals['base_url_general'] . 'subs';
    $subclass = 'brown';

    echo $output = Haanga::Load('best_stories.html', compact('links', 'title', 'subclass', 'url'), true);

    memcache_madd($key, $output, 300);
}

// Print the "message" of the sub, if it exists
function do_sub_message_right()
{
    global $globals, $current_user;

    if ($globals['mobile'] || !$globals['submnm']) {
        return;
    }

    $properties = SitesMgr::get_extended_properties();
    $properties['message_html'] = LCPBase::html($properties['message']);

    $site = SitesMgr::get_info();

    // Check if the sub has a logo and calculate the width
    if ($site->media_id > 0 && $site->media_dim1 > 0 && $site->media_dim2 > 0) {
        $r = $site->media_dim1 / $site->media_dim2;

        if ($globals['mobile']) {
            $site->logo_height = $globals['media_sublogo_height_mobile'] * 2;
        } else {
            $site->logo_height = $globals['media_sublogo_height'] * 2;
        }

        $site->logo_width = round($r * $site->logo_height);
        $site->logo_url = Upload::get_cache_relative_dir($site->id) . '/media_thumb-sub_logo-' . $site->id . '.' . $site->media_extension . '?' . $site->media_date;
    }

    $site->followers = SitesMgr::get_followers();

    Haanga::Load('message_right.html', array(
        'site' => $site,
        'owner' => SitesMgr::get_owner(),
        'properties' => $properties,
        'user' => $current_user,
    ));
}



// Добавил показ пространства для поста
function do_sub_message_right_to()
{
    global $globals, $current_user;

    if ($globals['mobile']) {
        return;
    }

    $properties = SitesMgr::get_extended_properties();
    $properties['message_html'] = LCPBase::html($properties['message']);

    $site = SitesMgr::get_info();

    // Check if the sub has a logo and calculate the width
    if ($site->media_id > 0 && $site->media_dim1 > 0 && $site->media_dim2 > 0) {
        $r = $site->media_dim1 / $site->media_dim2;

        if ($globals['mobile']) {
            $site->logo_height = $globals['media_sublogo_height_mobile'] * 2;
        } else {
            $site->logo_height = $globals['media_sublogo_height'] * 2;
        }

        $site->logo_width = round($r * $site->logo_height);
        $site->logo_url = Upload::get_cache_relative_dir($site->id) . '/media_thumb-sub_logo-' . $site->id . '.' . $site->media_extension . '?' . $site->media_date;
    }

    $site->followers = SitesMgr::get_followers();

    Haanga::Load('message_right.html', array(
        'site' => $site,
        'owner' => SitesMgr::get_owner(),
        'properties' => $properties,
        'user' => $current_user,
    ));
}



// Print the "message" of the sub, if it exists
function do_sub_description()
{
    global $globals, $current_user;

    if (!$globals['mobile'] || !$globals['submnm']) {
        return;
    }

    $properties = SitesMgr::get_extended_properties();
    $properties['message_html'] = LCPBase::html($properties['message']);

    $site = SitesMgr::get_info();

    // Check if the sub has a logo and calculate the width
    if ($site->media_id > 0 && $site->media_dim1 > 0 && $site->media_dim2 > 0) {
        $r = $site->media_dim1 / $site->media_dim2;

        if ($globals['mobile']) {
            $site->logo_height = $globals['media_sublogo_height_mobile'] * 2;
        } else {
            $site->logo_height = $globals['media_sublogo_height'] * 2;
        }

        $site->logo_width = round($r * $site->logo_height);
        $site->logo_url = Upload::get_cache_relative_dir($site->id) . '/media_thumb-sub_logo-' . $site->id . '.' . $site->media_extension . '?' . $site->media_date;
    }

    $site->followers = SitesMgr::get_followers();

    Haanga::Load('sub_description.html', array(
        'site' => $site,
        'owner' => SitesMgr::get_owner(),
        'properties' => $properties,
        'user' => $current_user,
    ));
}


//evg
function do_my_subs()
{
	
    global $db, $globals, $current_user;
    $subs = SitesMgr::get_subscriptions($current_user->user_id);

	foreach ($subs as $sub) {
            if (!$sub->enabled) {
                continue;
            }

            $sub->site_info = SitesMgr::get_info($sub->id);
			
			
	            // Check if the sub has a logo and calculate the width
            if ($sub->site_info->media_id > 0 && $sub->site_info->media_dim1 > 0 && $sub->site_info->media_dim2 > 0) {
                $r = $sub->site_info->media_dim1 / $sub->site_info->media_dim2;

                if ($globals['mobile']) {
                    $sub->site_info->logo_height = $globals['media_sublogo_height_mobile'];
                } else {
                    $sub->site_info->logo_height = $globals['media_sublogo_height'];
                }

                $sub->site_info->logo_width = round($r * $sub->site_info->logo_height);
                $sub->site_info->logo_url = Upload::get_cache_relative_dir($sub->site_info->id) . '/media_thumb-sub_logo-' . $sub->site_info->id . '.' . $sub->site_info->media_extension . '?' . $sub->site_info->media_date;
            }

            $followed_subs[] = $sub;
			

 }
 

 Haanga::Load('my_subs.html', compact('subs'));
}


function do_sidebar_block($name = 'default')
{
    Haanga::Safe_Load('private/sidebar-block-' . $name . '.html');
}

function do_sidebar_preguntame()
{
    Haanga::Load('preguntame/home-sidebar.html', [
        'next' => Preguntame::next(),
        'previous' => Preguntame::previous()
    ]);
}

function print_follow_sub($id)
{
    global $current_user;

    Haanga::Load('sub_follow.html', array(
        'id' => $id,
        'user' => $current_user,
    ));
}

function get_data_widget_official_subs()
{
    global $globals;

    $cache = $globals['memcache_host'] ? 'widget_official_subs' : null;

    if (($cache === null) || !($subs = unserialize(memcache_mget($cache)))) {
        $subs = query_official_subs_for_widget();

        if ($cache) {
            memcache_madd($cache, serialize($subs), 1800);
        }
    }

    return $subs;
}

function query_official_subs_for_widget()
{
    global $globals, $db;

    if (empty($globals['widget_official_subs'])) {
        return false;
    }

    $subs = array();

    foreach (array_keys($globals['widget_official_subs']) as $name) {
        $sub = SitesMgr::get_info(SitesMgr::get_id($name));
        $sub->extra_info = $globals['widget_official_subs'][$name];

        $subs[$sub->id] = $sub;
    }

    if (empty($subs)) {
        return array();
    }

    $followers = $db->get_results('
        SELECT `subs`.`id`, COUNT(*) AS `count`
        FROM `subs`, `prefs`
        WHERE (
            `subs`.`id` IN (' . implode(',', array_keys($subs)) . ')
            AND `prefs`.`pref_key` = "sub_follow"
            AND `subs`.`id` = `prefs`.`pref_value`
        )
        GROUP BY `subs`.`id`;
    ');

    foreach ($followers as $row) {
        $subs[$row->id]->followers = $row->count;
    }

    return array_values($subs);
}

function responseJson($data, $success = true)
{
    if (is_integer($data)) {
        die((string)$data);
    }

    $response = array(
        'success' => $success,
        'data' => null,
        'message' => null
    );

    if (is_string($data)) {
        $response['message'] = $data;
    } else {
        $response['data'] = $data;
    }

    die(json_encode($response));
}
