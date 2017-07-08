<?php

define('COMPOSER_ROOT', __DIR__);

require 'vendor/autoload.php';
require 'config.php';

//$file = '/home/airbase/airbase-lxc/var/www/sync/airbase-forums-push/2015-12-17-18-30-56--post-4046917.md';

//B2\MyBB\Post::infonesy_import(B2\Infonesy\FileSync::load_file($file));


//B2\MyBB\Topic::infonesy_import(B2\Infonesy\FileSync::load_file('/home/airbase/airbase-lxc/var/www/sync/airbase-forums-push/topic-16347.json'));

B2\MyBB\Forum::infonesy_import(B2\Infonesy\FileSync::load_file('/home/airbase/airbase-lxc/var/www/sync/airbase-forums-push/forum-25.json'));

//B2\MyBB\User::infonesy_import(B2\Infonesy\FileSync::load_file('/home/airbase/airbase-lxc/var/www/sync/airbase-forums-push/user-5587.json'));

//B2\MyBB\Post::infonesy_import(B2\Infonesy\FileSync::load_file('/home/airbase/airbase-lxc/var/www/sync/airbase-forums-push/2015-12-18-09-46-01--post-4047510.md'));

//B2\MyBB\Forum::infonesy_import(B2\Infonesy\FileSync::load_file('/home/airbase/airbase-lxc/var/www/sync/airbase-forums-push/category-8.json'));
