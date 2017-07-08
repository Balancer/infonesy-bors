<?php

require 'vendor/autoload.php';
bors::init();

$json_dir = '/var/www/sync/infonesy-airbase-htz';


foreach(glob("$json_dir/*") as $file)
{
	$data = \Infonesy\Transport\FileSync::load_file($file);

	echo $file, PHP_EOL;

	switch($data['Type'])
	{
		case 'User':
			Infonesy\Driver\Flarum\User::find_or_create($data);
			break;
		case 'Post':
		case 'News':
		case 'Comment':
		case 'Article':
			Infonesy\Driver\Flarum\Post::find_or_create($data);
			break;
/*
		case 'Topic':
			B2\MyBB\Topic::infonesy_import($data);
			break;
		case 'Forum':
		case 'Category':
			B2\MyBB\Forum::infonesy_import($data);
			break;
		case 'Attach':
//			B2\MyBB\Topic::infonesy_import($data);
			break;
		default:
			throw new Exception("Unknown type ".$data['Type']);
*/
	}
}
