<?php
// The source code packaged with this file is Free Software, Copyright (C) 2011 by
// Ricardo Galli <gallir at gmail dot com>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//      http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

require_once __DIR__.'/config.php';

$globals['force_ssl'] = false; // We open the bar always as http to allow loading no https pages

require_once mnminclude.'html1.php';

$url_args = $globals['path'];
$id = intval($globals['path'][1]);

if (! $id > 0 || ! ($link = Link::from_db($id))) {
    do_error(_('ссылка не найдена'), 404);
}

// Mark as read, add click if necessary
$link->add_click();

if ($globals['https'] && ! preg_match('/^https:/', $link->url)) {
    die(redirect($link->url));
}

$link->title = text_to_summary($link->title, 80);

$globals['security_key'] = get_security_key();

setcookie('k', $globals['security_key'], 0, $globals['base_url']);

// From libs/html1.php do_header()
header('Content-Type: text/html; charset=utf-8');

// From libks/link.php print_summary()
$link->is_votable();
$link->permalink = $link->get_permalink();
$link->can_vote_negative = !$link->voted && $link->votes_enabled && $link->negatives_allowed(true);
$link->get_box_class();

$vars = compact('type');
$vars['self'] = $link;

$globals['extra_css'][] = 'bar.css';

do_header($link->title, 'external');

Haanga::Load("link_bar.html", $vars);
