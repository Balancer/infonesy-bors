<?php

define('FLARUM_DIR', '/var/www/flarum.local');

require 'vendor/autoload.php';
require FLARUM_DIR.'/vendor/autoload.php';

bors::init();

$post = \Infonesy\Driver\Flarum\Post::create([
    'author_id' => 1,
    'topic_id' => 1,
    'text' => '@Balancer#1  Hello **world**! (with Markdown): '.date('%r'),
    'create_time' => \Carbon\Carbon::now('utc')->toDateTimeString(),
]);

echo "Posted as " . $post->id();
