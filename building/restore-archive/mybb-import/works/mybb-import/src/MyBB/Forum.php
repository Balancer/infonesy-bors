<?php

namespace B2\MyBB;

use \Respect\Relational\Sql;

class Forum extends Object
{
	var $was_new = true;

	function parent_forum()
	{
		if(empty($this->data_map->pid))
			return NULL;

		return new Forum($this->pid);
	}

	function parentlist_string()
	{
		if(empty($this->data_map->pid))
			return $this->fid;

		return join(',', [$this->parent_forum()->parentlist_string(), $this->fid]);
	}

	static function find_by_uuid($forum_uuid, $data=[])
	{
		if(!$forum_uuid)
			throw new \Exception("Empty forum UUID");

		$forum = new Forum(NULL);

		$map = $forum->mapper->mybb_forums(['uuid' => $forum_uuid])->fetch();

		if($map)
		{
			$forum->data_map = $map;
			$forum->was_new = false;
		}
		elseif(!$data)
			return NULL;

		if(!empty($GLOBALS['mybb_infonesy_import_save_ids']) && preg_match('/^.+\.(\d+)$/', $forum_uuid, $m))
		{
			// Пробуем прописать оригинальный ID.
			$original_id = $m[1];
			$map = Mapper::factory()->mybb_forums($original_id)->fetch();

			// Если под оригинальным ID в нашей БД ничего нет, то удобно сохраниться под ним:
			if(!$map)
			{
				if($forum->fid)
					Connector::instance()->query('UPDATE forums SET id='.$original_id.' WHERE id='.$forum->fid);

				$forum = new Forum($original_id);
			}
		}

		if(!empty($data['Type']) && empty($forum->data_map->type))
		{
			if($data['Type'] == 'Category')
				$forum->type = 'c';

			if($data['Type'] == 'Forum')
				$forum->type = 'f';
		}

		if(empty($forum->data_map->uuid))
		{
			$forum->data_map->uuid = $forum_uuid;

			$forum->disporder = 500;
			$forum->active = 1;
			$forum->open = 1;

			$forum->allowmycode = 1;
			$forum->allowsmilies = 1;
			$forum->allowimgcode = 1;
			$forum->allowvideocode = 1;
			$forum->allowpicons = 1;
			$forum->allowtratings = 1;
			$forum->usepostcounts = 1;
			$forum->usethreadcounts = 1;
			$forum->showinjump = 1;

			if(empty($forum->data_map->pid))
			{
				if($forum->type == 'c')
					$forum->data_map->pid = @$GLOBALS['mybb_quarantine_category_id'] ? $GLOBALS['mybb_quarantine_category_id'] : 1;
				elseif($forum->type == 'f')
					$forum->data_map->pid = @$GLOBALS['mybb_quarantine_forum_id'] ? $GLOBALS['mybb_quarantine_forum_id'] : 1;
			}
		}

		if(empty($forum->data_map->name))
			$forum->data_map->name = empty($data['Title']) ? '???' : $data['Title'];

		if(empty($forum->data_map->type))
			throw new \Exception("Can't find type of Forum ".$forum_uuid.": ".print_r($data, true));

		$forum->save();

		return $forum;
	}

	function sub_ids()
	{
		return array_column(Connector::instance()->query('SELECT fid FROM mybb_forums WHERE parentlist LIKE "'.$this->parentlist.'%"')->fetchAll(), 'fid');
	}


	function update()
	{
		$this->threads	= Connector::instance()->query('SELECT COUNT(*) FROM mybb_threads WHERE fid='.$this->fid())->fetchColumn(0);
		$this->posts	= Connector::instance()->query('SELECT SUM(`replies`+1) FROM mybb_threads WHERE fid='.$this->fid())->fetchColumn(0);

		$this->parentlist = $this->parentlist_string();

		$last_topic = NULL;
		foreach($this->sub_ids() as $fid)
		{
			$last_topic_check  = $this->mapper->mybb_threads(['fid' => $fid])->fetch(Sql::orderBy('lastpost DESC'));

			if(is_null($last_topic) || ($last_topic_check && $last_topic_check->lastpost > $last_topic->lastpost))
				$last_topic = $last_topic_check;
		}

		if($last_topic)
		{
			$this->lastpost	= $last_topic->lastpost;
			$this->lastposter	= $last_topic->lastposter;
			$this->lastposttid	= $last_topic->tid;

			$last_post  = $this->mapper->mybb_posts(['tid' => $last_topic->tid])->fetch(Sql::orderBy('dateline DESC'));

			if($last_post && $last_post->subject)
				$this->lastpostsubject	= $last_post->subject;
			else
				$this->lastpostsubject	= $last_topic->subject;

		}
//		else
//			echo "Not found last topic for forum id=".$this->fid;

		$this->save();

		Connector::instance()->query('UPDATE mybb_threads SET fid='.$this->fid().' WHERE forum_uuid="'.$this->uuid().'"');
	}

	static function infonesy_import($data)
	{
		if(!$data || empty($data['UUID']))
			return;

		$forum = Forum::find_by_uuid($data['UUID'], $data);

		Mapper::set_fields($forum, $data, [
			'uuid' => 'UUID',
		], [
			'name' => 'Title',
			'description' => 'Description',
		]);

		if(!empty($data['ParentUUID']))
		{
			$parent = Forum::find_by_uuid($data['ParentUUID']);
			if($parent && $forum->was_new)
				$forum->pid = $parent->fid();

			$forum->parent_uuid = $data['ParentUUID'];
		}
		elseif(!empty($data['CategoryUUID']))
		{
			$category = Forum::find_by_uuid($data['CategoryUUID']);
			if($category && $forum->was_new)
				$forum->pid = $category->fid();

			$forum->parent_uuid = $data['CategoryUUID'];
		}

		if(empty($forum->data_map->pid) && $forum->was_new)
			$forum->pid = @$GLOBALS['mybb_quarantine_category_id'] ? $GLOBALS['mybb_quarantine_category_id'] : 1;

		$forum->save();

		$forum->update();

		Bridge::cache_update_forums();
	}
}
