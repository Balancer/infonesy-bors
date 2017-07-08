<?php

require 'setup.php';

$converter = new League\HTMLToMarkdown\HtmlConverter(['strip_tags' => true, 'header_style' => 'atx']);

foreach(Bal\TtRss\UserEntry::find(['is_published' => true])->order('-id')->all(10) as $user_entry)
{
    $feed_entry = $user_entry->entry();

	$html = $feed_entry->html();

	$html = preg_replace('!(<br[^>]*>)-[  ]!', '$1— ', $html);

	$md = $converter->convert($html);

	$author = $feed_entry->author();
	if(!$author)
		$author = $user_entry->feed()->title();

/*
    echo "# {$feed_entry->title()} (".bors_strlen($md).")\n";
    echo "Author: {$author}\n\n";
    echo "$md\n\n";
    echo "// {$feed_entry->link()}\n\n";
*/
	$link = new \B2\Obj(NULL);
	$link->set_attrs([
		'title' => $feed_entry->title(),
		'create_time' => strtotime($feed_entry->updated()),
		'modify_time' => strtotime($feed_entry->date_updated()),
//		'keywords'		=>
	]);

	$helper = new \B2\Infonesy\Link($link);
	$helper->set_attr('infonesy_dir', '/home/balancer/Works/composer/vendor/balancer/infonesy-bors/src/Infonesy');

	echo $helper->infonesy_push();
	exit();
}
