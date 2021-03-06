<?php

require_once __DIR__.'/config.php';
require_once mnminclude.'html1.php';

$page_size = $globals['page_size'];

$range_names  = array(_('24 часа'), _('48 часов'), _('неделя'), _('месяц'), _('год'), _('все'));
$range_values = array(1, 2, 7, 30, 365, 0);

$current_page = get_current_page();
$offset=($current_page-1)*$page_size;

$globals['description'] = 'Популярные истории на Sugata. Если ваши истории нравятся участникам, их комментируют и у них хорошая карма, то они будут в этом разделе.';

// Select a month and year
if (!empty($_GET['month']) && !empty($_GET['year']) && ($month = (int) $_GET['month']) > 0 && ($year = (int) $_GET['year'])) {
    $sql = "SELECT SQL_CACHE link_id, link_votes as votes FROM links, sub_statuses WHERE id = ".SitesMgr::my_id()." AND YEAR(date) = $year AND MONTH(date) = $month AND status = 'published' AND link = link_id ORDER BY link_votes DESC ";
    $time_link = "YEAR(date) = $year AND MONTH(date) = $month AND";
} else {
    // Select from a start date
    $from = intval($_GET['range']);
    if ($from >= count($range_values) || $from < 0) {
        $from = 0;
    }

    // Use memcache if available
    if ($globals['memcache_host'] && $current_page < 4) {
        $memcache_key = 'topstories_'.$globals['site_shortname'].$from.'_'.$current_page;
    }

    if ($range_values[$from] > 0) {
        // we use this to allow sql caching
        $from_time = '"'.date("Y-m-d H:i:00", time() - 86400 * $range_values[$from]).'"';
        $sql = "SELECT SQL_CACHE link_id, link_votes-link_negatives as votes FROM links, sub_statuses WHERE id = ".SitesMgr::my_id()." AND date > $from_time AND status = 'published' AND link_id = link ORDER BY votes DESC ";
        $time_link = "date > $from_time AND";
    } else {
        // Default
        $sql = "SELECT SQL_CACHE link_id, link_votes-link_negatives as votes FROM links, sub_statuses WHERE id = ".SitesMgr::my_id()." AND status = 'published' AND link = link_id ORDER BY votes DESC ";
        $time_link = '';
    }
}

if (!($memcache_key
        && ($rows = memcache_mget($memcache_key.'rows'))
        && ($links = unserialize(memcache_mget($memcache_key))))) {
    // It's not in cache, or memcache is disabled
    $rows = $db->get_var("SELECT count(*) FROM sub_statuses WHERE id = ".SitesMgr::my_id()." AND $time_link status = 'published'");
    if ($rows > 0) {
        $links = $db->get_results("$sql LIMIT $offset,$page_size");
        if ($memcache_key) {
            if ($range_values[$from] > 2) {
                $ttl = 86400;
            } else {
                $ttl = 1800;
            }
            memcache_madd($memcache_key.'rows', $rows, $ttl);
            memcache_madd($memcache_key, serialize($links), $ttl);
        }
    }
}
do_header(_("Популярные"), 'Популярные');


//do_header(_('Популярное') . 'Популярное' . $globals['site_name']);
$globals['tag_status'] = 'published';

/*** SIDEBAR ****/
echo '<div id="sidebar">';
do_banner_right();
do_active_stories();
do_banner_promotions();
do_last_subs('published', 5, 'link_votes');
do_best_comments();
//do_vertical_tags('published');

do_footer_menu();

echo '</div>' . "\n";
/*** END SIDEBAR ***/

echo '<div id="newswrap">'."\n";

if ($links) {
    $counter = 0;
    foreach ($links as $dblink) {
        $link = Link::from_db($dblink->link_id);
		$link->max_len = 800;
        $link->show_clicks = true;
        $link->print_summary();
        $counter++;
        Haanga::Safe_Load('private/ad-interlinks.html', compact('counter', 'page_size'));
    }
}
do_pages($rows, $page_size);



echo '</div>'."\n";


do_footer();
