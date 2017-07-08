<?php

namespace B2\MyBB;

class Post extends Object
{
	function topic()
	{
		return new Topic($this->tid());
	}

	function find_by_uuid($post_uuid, $data=[])
	{
		$post = new Post(NULL);

		$map = $post->mapper->mybb_posts(['uuid' => $post_uuid])->fetch();

		if($map)
			$post->data_map = $map;
		elseif(!$data)
			return NULL;

		// Пробуем прописать оригинальный ID.
		if(!empty($GLOBALS['mybb_infonesy_import_save_ids']) && preg_match('/^.+\.(\d+)$/', $post_uuid, $m))
		{
			$original_id = $m[1];
			$map = Mapper::factory()->mybb_posts($original_id)->fetch();

			// Если под оригинальным ID в нашей БД ничего нет, то удобно сохраниться под ним:
			if(!$map)
			{
				if($post->pid)
					Connector::instance()->query('UPDATE mybb_posts SET pid='.$original_id.' WHERE pid='.$post->pid);
				$post = new Post($original_id);
			}
		}

		$post->uuid = $post_uuid;

		$post->save();

		return $post;
	}


	static function infonesy_import($data)
	{
		if(empty($data['TopicUUID']))
			throw new \Exception("Undefined TopicUUID for ".print_r($data, true));

		$post = Post::find_by_uuid($data['UUID'], $data);
		// Заполняем объект — строку для записи
		// [UUID] => ru.balancer.board.post.4043285
		// $post->uuid = $data['UUID']; — прописано уже при загрузке
		// [Node] => ru.balancer.board
		// [Title] => РККФ в ВОВ. [Вованыч_1977#14.12.15 20:50]
		$post->username = $data['Author'];
		$post->poster_email_md5 = @$data['AuthorEmailMD5'];
		// [Date] => Mon, 14 Dec 2015 20:50:15 +0300
		$post->dateline =  strtotime($data['Date']);
		// [Modify] => Mon, 14 Dec 2015 20:50:15 +0300
		// [Type] => Post
		// [Markup] => lcml
		// [AnswerTo] => ru.balancer.board.post.4043266
		$post->message =  $data['Text'];

		if(!empty($data['AnswerTo']))
		{
			$reply_post = Post::find_by_uuid($data['AnswerTo']);
			if($reply_post)
				$post->replyto = $reply_post->pid;

			$post->replyto_uuid = $data['AnswerTo'];
		}

		$topic = Topic::find_by_uuid($data['TopicUUID']);
		if($topic)
			$post->tid = $topic->tid();
		else
			$post->tid = @$GLOBALS['mybb_quarantine_topic_id'];

		$post->topic_uuid = $data['TopicUUID'];
		$post->visible = 1;

		$md5 = @$data['AuthorEmailMD5'];
		if(!$md5)
			$md5 = @$data['AuthorMD'];

		$poster = User::find_by_email_md5($md5, ['username' => $data['Author'], 'usergroup' => 2, 'uuid' => @$data['AuthorUUID']]);

		$post->poster_uuid = @$data['AuthorUUID'];
		$post->uid = $poster->uid;

		$post->save();

		if($topic)
			$topic->update();
	}
}
