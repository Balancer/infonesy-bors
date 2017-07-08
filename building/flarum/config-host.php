<?php

define('FLARUM_DIR', '/var/www/flarum.local');

require FLARUM_DIR.'/vendor/autoload.php';

$flarum_conf = require FLARUM_DIR.'/config.php';

extract($flarum_conf['database']);

config_set('flarum_db', $database);

mysql_access(config('flarum_db'), $username, $password, $host);

config_set('flarum.quarantine.topic_id', 2);

set_def($_SERVER, 'REQUEST_URI', '/');
set_def($_SERVER, 'HTTP_USER_AGENT', 'infonesy converter');
