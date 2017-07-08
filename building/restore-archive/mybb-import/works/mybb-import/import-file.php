<?php

define('COMPOSER_ROOT', __DIR__);

require 'vendor/autoload.php';
require 'config.php';

	$file = $argv[1];

	if(!is_file($file))
		return;

	$data = B2\Infonesy\FileSync::load_file($file);

	echo $file, PHP_EOL;

	switch($data['Type'])
	{
		case 'Post':
		case 'News':
		case 'Comment':
		case 'Article':
			B2\MyBB\Post::infonesy_import($data);
			break;
		case 'Topic':
			B2\MyBB\Topic::infonesy_import($data);
			break;
		case 'Forum':
		case 'Category':
			B2\MyBB\Forum::infonesy_import($data);
			break;
		case 'User':
			B2\MyBB\User::infonesy_import($data);
			break;
		case 'Attach':
//			B2\MyBB\User::infonesy_import($data);
			break;
		default:
			throw new Exception("Unknown type ".$data['Type']);
	}
