<?php

if(!defined('COMPOSER_ROOT'))
{
	if(is_dir($d = $_SERVER['DOCUMENT_ROOT'].'/../composer'))
		define('COMPOSER_ROOT', $d);
	elseif(is_dir($d = $_SERVER['DOCUMENT_ROOT'].'/composer'))
		define('COMPOSER_ROOT', $d);
	else
		return NULL;

	require COMPOSER_ROOT.'/vendor/autoload.php';
	require_once COMPOSER_ROOT.'/config.php';
}

if(!defined('IN_MYBB'))
{
	die('This file cannot be accessed directly.');
}

// cache templates - this is important when it comes to performance
// THIS_SCRIPT is defined by some of the MyBB scripts, including index.php
if(defined('THIS_SCRIPT'))
{
	global $templatelist;

	if(isset($templatelist))
	{
		$templatelist .= ',';
	}

	if(THIS_SCRIPT== 'index.php')
	{
		$templatelist .= 'hello_index, hello_message';
	}
	elseif(THIS_SCRIPT== 'showthread.php')
	{
		$templatelist .= 'hello_post, hello_message';
	}
}

// Add our hello_index() function to the index_start hook so when that hook is run our function is executed
//$plugins->add_hook('postbit', 'new_reply_infonesy_push_postbit');
$plugins->add_hook('datahandler_post_insert_post_end', 'new_reply_infonesy_push_post_insert');
// $plugins->add_hook('newreply_do_newreply_end', 'new_reply_infonesy_push_newreply_end');

function new_reply_infonesy_push_info()
{
	/**
	 * Array of information about the plugin.
	 * name: The name of the plugin
	 * description: Description of what the plugin does
	 * website: The website the plugin is maintained at (Optional)
	 * author: The name of the author of the plugin
	 * authorsite: The URL to the website of the author (Optional)
	 * version: The version number of the plugin
	 * compatibility: A CSV list of MyBB versions supported. Ex, '121,123', '12*'. Wildcards supported.
	 * codename: An unique code name to be used by updated from the official MyBB Mods community.
	 */
	return array(
		'name'			=> 'Infonesy post push',
		'description'	=> 'Infonesy swarm testing',
		'website'		=> 'http://balancer.ru',
		'author'		=> 'Balancer',
		'authorsite'	=> 'http://www.balancer.ru',
		'version'		=> '0.1',
		'compatibility'	=> '18*',
		'codename'		=> 'new_reply_infonesy_push'
	);
}


/*
 * _is_installed():
 *   Called on the plugin management page to establish if a plugin is already installed or not.
 *   This should return TRUE if the plugin is installed (by checking tables, fields etc) or FALSE
 *   if the plugin is not installed.
*/
function new_reply_infonesy_push_is_installed()
{
	return true;
}


/*
 * @param $post Array containing information about the current post. Note: must be received by reference otherwise our changes are not preserved.
*/
function new_reply_infonesy_push_postbit(&$post)
{
	global $settings, $mybb, $lang, $templates;

	if($mybb->input['action'] != 'thread')
	{
		@file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/dump-postbit-mybb-input.txt', print_r($mybb->input, true), FILE_APPEND);
		@file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/dump-postbit-post.txt', print_r($post, true), FILE_APPEND);
	}

	if($mybb->input['action'] != 'do_newreply')
	{
		if($mybb->input['action'] != 'thread')
		@file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/dump-postbit-actions.txt', print_r($mybb->input, true), FILE_APPEND);
		return;
	}

	@file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/dump-do_newreply-mybb-input.txt', print_r($mybb->input, true));
	@file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/dump-do_newreply-mybb.txt', print_r($mybb, true));
	@file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/dump-do_newreply-post.txt', print_r($post, true));

	$storage = $GLOBALS['mybb_infonesy']['push_dir'];

	$file = $storage.'/'.date('Ymd-His').'--post-'.$post['pid'].'.md';

	@file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/file.txt', $file);

	$meta = [
		'UUID'	  => $GLOBALS['mybb_infonesy']['post_uuid_base'].$post['pid'],
		'Node'	  => $GLOBALS['mybb_infonesy']['node_uuid'],
		'TopicUUID' => $GLOBALS['mybb_infonesy']['topic_uuid_base'].$post['tid'],
//		'Title' => '',
	];

//	if($t = $this->title())
//		$meta['Title'] = $t;

	$meta = array_merge($meta, [
		'Author'	=> $post['username'],
		'AuthorEmailMD5'	=> md5($post['email']),
		'AuthorUUID'=> $GLOBALS['mybb_infonesy']['user_uuid_base'].$post['uid'],
		'Date'	  => date('r', $post['dateline']),
		'Type'	  => 'Post',
		'Markup'	=> 'bbcode.mybb',
	]);

	$dumper = new Symfony\Component\Yaml\Dumper();

	$md = "---\n";
	$md .= $dumper->dump($meta, 2);
	$md .= "---\n\n";

	$md .= trim(str_replace("\r", "", $mybb->input['message']))."\n";

	file_put_contents($file, $md);
	chmod($file, 0666);

	return;
}

function new_reply_infonesy_push_post_insert(&$post)
{
	global $settings, $mybb, $lang, $templates;

	@file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/dump-post_insert-mybb-input.txt', print_r($mybb->input, true), FILE_APPEND);
	@file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/dump-post_insert-post.txt', print_r($post, true), FILE_APPEND);

	$data = [
		'UUID'	  => $GLOBALS['mybb_infonesy']['post_uuid_base'].$post->pid,
		'Node'	  => $GLOBALS['mybb_infonesy']['node_uuid'],
		'TopicUUID' => $GLOBALS['mybb_infonesy']['topic_uuid_base'].$post->data['tid'],
		'Author'	=> $post->data['username'],
		'AuthorEmailMD5'	=> md5($mybb->user['email']),
		'AuthorUUID'=> $GLOBALS['mybb_infonesy']['user_uuid_base'].$mybb->user['uid'],
		'Date'	  => date('r', $post->data['dateline']),
		'Type'	  => 'Post',
		'Markup'	=> 'bbcode.mybb',
		'Message' => $post->data['message'],
		'AnswerTo' => $GLOBALS['mybb_infonesy']['post_uuid_base'].$post->data['replyto'],
	];

	infonesy_export_post($post->pid, $data);
}

function infonesy_export_post($pid, $data)
{
	$storage = $GLOBALS['mybb_infonesy']['push_dir'];
	$file = $storage.'/'.date('Ymd-His').'--post-'.$pid.'.md';

	$message = $data['Message'];
	unset($data['Message']);

	$dumper = new Symfony\Component\Yaml\Dumper();

	$md = "---\n";
	$md .= $dumper->dump($data, 2);
	$md .= "---\n\n";


	$md .= trim(str_replace("\r", "", $message))."\n";

	file_put_contents($file, $md);
	chmod($file, 0666);
}
