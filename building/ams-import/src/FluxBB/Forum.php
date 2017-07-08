<?php

namespace B2\FluxBB;

use \Respect\Relational\Sql;

class Forum extends Object
{
	static function find_by_uuid($forum_uuid)
	{
		if(!$forum_uuid)
			throw new \Exception("Empty forum UUID");

		$forum = new Forum(NULL);

		$map = $forum->mapper->forums(['uuid' => $forum_uuid])->fetch();

		if($map)
			$forum->data_map = $map;

		// Пробуем прописать оригинальный ID.
		if(preg_match('/^.+\.(\d+)$/', $forum_uuid, $m))
		{
			$original_id = $m[1];
			$map = Mapper::factory()->forums($original_id)->fetch();

			// Если под оригинальным ID в нашей БД ничего нет, то удобно сохраниться под ним:
			if(!$map)
			{
				if($forum->id)
					Connector::instance()->query('UPDATE forums SET id='.$original_id.' WHERE id='.$forum->id);

				$forum = new Forum($original_id);
			}
		}

		$forum->data_map->uuid = $forum_uuid;

		if(empty($forum->data_map->forum_name))
			$forum->data_map->forum_name = '???';

		if(empty($forum->data_map->cat_id))
			$forum->data_map->cat_id = @$GLOBALS['fluxbb_quarantine_cat_id'] ? $GLOBALS['fluxbb_quarantine_cat_id'] : 1;

		$forum->save();

		return $forum;
	}

	static function infonesy_import($data)
	{
		$forum = Forum::find_by_uuid($data['UUID']);

		$forum->uuid = $data['UUID'];
		$forum->forum_name = $data['Title'];
		if(!empty($data['CategoryUUID']))
		{
			$forum->cat_id = Category::find_by_uuid($data['CategoryUUID'])->id();
			$forum->category_uuid = $data['CategoryUUID'];
		}
		else
			$forum->cat_id = @$GLOBALS['fluxbb_quarantine_cat_id'] ? $GLOBALS['fluxbb_quarantine_cat_id'] : 1;

		$forum->save();

		$forum->update();
	}

	function update()
	{
		$this->num_topics		= Connector::instance()->query('SELECT COUNT(*) FROM topics WHERE forum_id='.$this->id())->fetchColumn(0);
		$this->num_posts		= Connector::instance()->query('SELECT SUM(`num_replies`+1) FROM topics WHERE forum_id='.$this->id())->fetchColumn(0);

		$last_topic  = $this->mapper->topics(['forum_id' => $this->id])->fetch(Sql::orderBy('posted DESC'));

		if($last_topic)
		{
			$this->last_post	= $last_topic->last_post;
			$this->last_post_id	= $last_topic->last_post_id;
			$this->last_poster	= $last_topic->last_poster;
		}
		else
			echo "Not found last topic for forum id=".$this->id;

		$this->save();

		Connector::instance()->query('UPDATE topics SET forum_id='.$this->id().' WHERE forum_uuid="'.$this->uuid().'"');
	}
}
