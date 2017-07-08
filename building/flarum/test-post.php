<?php

// define('FLARUM_DIR', '/var/www/flarum.local');

require 'vendor/autoload.php';
bors::init();

$json_file = '/var/www/sync/infonesy-airbase-htz/ru.balancer.board.post.4310518.md';

$data = Infonesy\Transport\FileSync::load_file($json_file);

// var_dump($data);

$flarum_post = Infonesy\Driver\Flarum\Post::find_or_create($data);

var_dump($flarum_post->data);
