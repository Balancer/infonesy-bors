<?php

$GLOBALS['mybb_connector'] = new PDO('mysql:host=10.0.3.1;port=3306;dbname=MYBB','mybb','xxxxxxxx');

$GLOBALS['mybb_quarantine_category_id'] = 3;
$GLOBALS['mybb_quarantine_forum_id'] = 4;
$GLOBALS['mybb_quarantine_topic_id'] = 4;

$GLOBALS['mybb_root'] = '/home/docker-vhosts/unlimit-talks.tk/mybb_1806/Upload';

define('IN_MYBB', true);
$root = $GLOBALS['mybb_root'];
require_once $root.'/inc/init.php';
