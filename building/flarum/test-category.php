<?php

// define('FLARUM_DIR', '/var/www/flarum.local');

require 'vendor/autoload.php';
bors::init();

$json_file = '/var/www/sync/infonesy-airbase-htz/ru.balancer.board.category.4.json';

$data = Infonesy\Transport\FileSync::load_file($json_file);

$flarum_tag = Infonesy\Driver\Flarum\Tag::find_or_create($data);

var_dump($flarum_tag->data);
