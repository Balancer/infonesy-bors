<?php

namespace B2\MyBB;

use \Respect\Relational\Sql;

class Topic extends Object
{
	function table_name() { return 'mybb_threads'; }

	static function infonesy_import($data)
	{
		$thread = Topic::find_by_uuid($data['UUID'], $data);
		$thread->uuid = $data['UUID'];
		$thread->subject = $data['Title'];
		$thread->dateline =  strtotime($data['Date']);

		$thread->username = $data['Author'];

		$md5 = @$data['AuthorEmailMD5'];
		if(!$md5)
			$md5 = @$data['AuthorMD'];

		$poster = User::find_by_email_md5($md5, ['username' => $data['Author'], 'usergroup' => 2, 'uuid' => @$data['AuthorUUID']]);

		$thread->poster_uuid = @$data['AuthorUUID'];
		$thread->uid = $poster->uid;

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

		$forum = Forum::find_by_uuid($data['ForumUUID']);

		if($forum)
			$thread->fid = $forum->fid();

		$thread->forum_uuid	= @$data['ForumUUID'];
		$thread->save();

		$thread->update();
	}

	function find_by_uuid($thread_uuid, $data=[])
	{
		$thread = new Topic(NULL);

		$map = $thread->mapper->mybb_threads(['uuid' => $thread_uuid])->fetch();

		if($map)
			$thread->data_map = $map;
		elseif(!$data)
			return NULL;

		// Пробуем прописать оригинальный ID.
		if(!empty($GLOBALS['mybb_infonesy_import_save_ids']) && preg_match('/^.+\.(\d+)$/', $thread_uuid, $m))
		{
			$original_id = $m[1];
			$map = Mapper::factory()->mybb_threads($original_id)->fetch();

			// Если под оригинальным ID в нашей БД ничего нет, то удобно сохраниться под ним:
			if(!$map)
			{
				if($thread->tid)
					Connector::instance()->query('UPDATE mybb_threads SET id='.$original_id.' WHERE id='.$thread->tid);

				$thread = new Topic($original_id);
			}
		}

		$thread->data_map->uuid = $thread_uuid;

		$thread->data_map->visible = 1;

		if(empty($thread->data_map->subject))
			$thread->data_map->subject = empty($data['Title']) ? '???' : $data['Title'];

		$thread->save();

		return $thread;
	}

	function forum()
	{
		if($this->fid())
			return new Forum($this->fid());

		return self::find_by_uuid($this->forum_uuid());
	}

	function update()
	{
//		var_dump($this->tid, $this->forum_id, $this->forum_uuid);

		$first_post	= $this->mapper->mybb_posts(['tid' => $this->tid()])->fetch(Sql::orderBy('dateline'));
		$last_post	= $this->mapper->mybb_posts(['tid' => $this->tid()])->fetch(Sql::orderBy('dateline DESC'));

		if($first_post)
		{
			$this->username		= $first_post->username;
			$this->firstpost	= $first_post->pid;
			$this->dateline		= $first_post->dateline;
		}

		if($last_post)
		{
			$this->lastpost			= $last_post->dateline;
			$this->lastposteruid	= $last_post->pid;
			$this->lastposter		= $last_post->username;
		}

		$this->replies		= Connector::instance()->query('SELECT COUNT(*) FROM mybb_posts WHERE tid='.$this->tid())->fetchColumn(0)-1;

		if($this->forum_uuid())
			$forum = Forum::find_by_uuid($this->forum_uuid());
		elseif($this->forum_id() && !$this->uuid())
			$forum = new Forum($this->forum_id());
		else
			$forum = new Forum(@$GLOBALS['mybb_quarantine_forum_id'] ? $GLOBALS['mybb_quarantine_forum_id'] : 1);

		if($forum)
			$this->fid = $forum->fid();
		else
			$this->fid = @$GLOBALS['mybb_quarantine_forum_id'];

		$this->save();

		Connector::instance()->query('UPDATE mybb_posts SET tid='.$this->tid().' WHERE tid IN (0,'.intval(@$GLOBALS['mybb_quarantine_topic_id']).') AND topic_uuid="'.$this->uuid().'"');

		if($forum)
			$forum->update();
	}
}
