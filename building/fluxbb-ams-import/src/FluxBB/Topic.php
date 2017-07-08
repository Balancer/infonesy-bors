<?php

namespace B2\FluxBB;

use \Respect\Relational\Sql;

class Topic extends Object
{
	static function infonesy_import($data)
	{
		$topic = Topic::find_by_uuid($data['UUID']);
		$topic->uuid = $data['UUID'];
		$topic->poster = $data['Author'];
		$topic->subject = $data['Title'];
		$topic->posted =  strtotime($data['Date']);

// first_post_id
// last_post
// last_post_id
// last_poster
// num_views
// num_replies
// closed
// sticky
// moved_to

		if(empty($data['ForumUUID']))
			throw new \Exception("Undefined ForumUUID for ".print_r($data, true));

		$topic->forum_id	= Forum::find_by_uuid($data['ForumUUID'])->id();
		$topic->forum_uuid	= $data['ForumUUID'];
		$topic->save();

		$topic->update();
	}

	function find_by_uuid($topic_uuid)
	{
		$topic = new Topic(NULL);

		$map = $topic->mapper->topics(['uuid' => $topic_uuid])->fetch();

		if($map)
			$topic->data_map = $map;

		// Пробуем прописать оригинальный ID.
		if(preg_match('/^.+\.(\d+)$/', $topic_uuid, $m))
		{
			$original_id = $m[1];
			$map = Mapper::factory()->topics($original_id)->fetch();

			// Если под оригинальным ID в нашей БД ничего нет, то удобно сохраниться под ним:
			if(!$map)
			{
				if($topic->id)
					Connector::instance()->query('UPDATE topics SET id='.$original_id.' WHERE id='.$topic->id);

				$topic = new Topic($original_id);
			}
		}

		$topic->data_map->uuid = $topic_uuid;

		if(empty($topic->data_map->subject))
			$topic->data_map->subject = '???';

		$topic->save();

		return $topic;
	}

	function forum()
	{
		if($this->forum_id())
			return new Forum($this->forum_id());

		return self::find_by_uuid($this->forum_uuid());
	}

	function update()
	{
//		var_dump($this->id, $this->forum_id, $this->forum_uuid);

		$first_post	= $this->mapper->posts(['topic_id' => $this->id()])->fetch(Sql::orderBy('posted'));
		$last_post	= $this->mapper->posts(['topic_id' => $this->id()])->fetch(Sql::orderBy('posted DESC'));

		if($first_post)
			$this->first_post_id	= $first_post->id;

		if($last_post)
		{
			$this->last_post		= $last_post->posted;
			$this->last_post_id		= $last_post->id;
			$this->last_poster		= $last_post->poster;
		}

		$this->num_replies		= Connector::instance()->query('SELECT COUNT(*) FROM posts WHERE topic_id='.$this->id())->fetchColumn(0)-1;

		if($this->forum_uuid())
			$forum = Forum::find_by_uuid($this->forum_uuid());
		elseif($this->forum_id() && !$this->uuid())
			$forum = new Forum($this->forum_id());
		else
			$forum = new Forum(@$GLOBALS['fluxbb_quarantine_forum_id'] ? $GLOBALS['fluxbb_quarantine_forum_id'] : 1);

		$this->forum_id = $forum->id();

		$this->save();

		Connector::instance()->query('UPDATE posts SET topic_id='.$this->id().' WHERE topic_id=0 AND topic_uuid="'.$this->uuid().'"');

		$forum->update();
	}
}
