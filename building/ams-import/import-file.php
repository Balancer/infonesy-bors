<?php

require 'vendor/autoload.php';
require 'config.php';

	$file = $argv[1];

	if(!is_file($file))
		return;

	$content = file_get_contents($file);

	if(preg_match('/\.bts$/', $file))
		return;
	elseif(preg_match('/\.json$/', $file))
		$data = json_decode($content, true);
	elseif(preg_match("/^---\n(.+?)\n---\n+(.*)$/s", trim($content), $m))
	{
		list($foo, $yaml, $src) = $m;

		$data = \Symfony\Component\Yaml\Yaml::parse($yaml);
		$data['Text'] = $src;
	}
	else
		throw new Exception("Oops [$file]: ".$content);

	echo $file, PHP_EOL;

	switch($data['Type'])
	{
		case 'Post':
		case 'News':
		case 'Comment':
		case 'Article':
			B2\FluxBB\Post::infonesy_import($data);
			break;
		case 'Topic':
			B2\FluxBB\Topic::infonesy_import($data);
			break;
		case 'Forum':
			B2\FluxBB\Forum::infonesy_import($data);
			break;
		case 'Category':
			B2\FluxBB\Category::infonesy_import($data);
			break;
		case 'User':
			B2\FluxBB\User::infonesy_import($data);
			break;
		default:
			throw new Exception("Unknown type ".$data['Type']);
	}
