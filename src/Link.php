<?php

namespace Infonesy;

class Link extends \B2\Obj
{
	function id()
	{
		return md5($this->link()->link());
	}

	function infonesy_uuid()
	{
		return Node::factory()->infonesy_uuid().'.link.' . $this->id();
	}

	function infonesy_dir()
	{
		throw new \Exception("Undefined directory for push");
	}

	function infonesy_push()
	{
		if(!$this->get('is_public_access', true))
			return;

		require_once BORS_CORE.'/inc/functions/fs/file_put_contents_lock.php';
		$storage = $this->infonesy_dir();

		$file = $storage.'/'.$this->infonesy_uuid().'.json';

		$link = $this->link();

		$data = [
			'UUID'		=> $this->infonesy_uuid(),
			'Node'		=> Node::factory()->infonesy_uuid(),
			'Title'		=> $link->title(),
			'Date'		=> date('r', $link->create_time()),
			'Modify'	=> date('r', $link->modify_time()),
			'Type'		=> 'Link',
			'Keywords'	=> $link->keywords(),
		];

		if($author = $link->author())
		{
			$data['Author']		= [
				'Title' 	=> $author->title(),
				'EmailMD5'	=> md5($author->email()),
				'UUID'		=> $author->infonesy_uuid(),
			];
		}

		file_put_contents_lock($file, json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		@chmod($file, 0666);
	}
}
