<?php

namespace B2\FluxBB;

class Post extends Object
{
	function topic()
	{
		return new Topic($this->topic_id());
	}

	function find_by_uuid($post_uuid)
	{
		$post = new Post(NULL);

		$map = $post->mapper->posts(['uuid' => $post_uuid])->fetch();

		if($map)
			$post->data_map = $map;

		// Пробуем прописать оригинальный ID.
		if(preg_match('/^.+\.(\d+)$/', $post_uuid, $m))
		{
			$original_id = $m[1];
			$map = Mapper::factory()->posts($original_id)->fetch();

			// Если под оригинальным ID в нашей БД ничего нет, то удобно сохраниться под ним:
			if(!$map)
			{
				if($post->id)
					Connector::instance()->query('UPDATE posts SET id='.$original_id.' WHERE id='.$post->id);
				$post = new Post($original_id);
			}
		}

		$post->uuid = $post_uuid;

		$post->save();

		return $post;
	}


	static function infonesy_import($data)
	{
		$post = Post::find_by_uuid($data['UUID']);
		// Заполняем объект — строку для записи
		// [UUID] => ru.balancer.board.post.4043285
		// $post->uuid = $data['UUID']; — прописано уже при загрузке
		// [Node] => ru.balancer.board
		// [TopicUUID] => ru.balancer.board.topic.92272
		// [Title] => РККФ в ВОВ. [Вованыч_1977#14.12.15 20:50]
		// [Author] => Вованыч_1977
		$post->poster = $data['Author'];
		// [AuthorMD] => 931f04765b7b0bb2aa09e2726f857364
		// [Date] => Mon, 14 Dec 2015 20:50:15 +0300
		$post->posted =  strtotime($data['Date']);
		// [Modify] => Mon, 14 Dec 2015 20:50:15 +0300
		// [Type] => Post
		// [Markup] => lcml
		// [AnswerTo] => ru.balancer.board.post.4043266
		$post->message =  $data['Text'];

		if(empty($data['TopicUUID']))
			throw new \Exception("Undefined TopicUUID for ".print_r($data, true));

		$post->topic_id		= Topic::find_by_uuid($data['TopicUUID'])->id();
		$post->poster_id	= User::find_by_email_md5($data['AuthorMD'], [
			'username' => $data['Author'],
			'group_id' => 4,
		])->id();

		$post->save();

		$post->topic()->update();
	}
}
