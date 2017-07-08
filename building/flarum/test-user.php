<?php

// define('FLARUM_DIR', '/var/www/flarum.local');

require 'vendor/autoload.php';
bors::init();

$json_file = '/var/www/sync/infonesy-airbase-htz/ru.balancer.board.user.84071.json';

$user_data = Infonesy\Transport\FileSync::load_file($json_file);

$flarum_user = Infonesy\Driver\Flarum\User::find_or_create($user_data);

var_dump($flarum_user->data);
