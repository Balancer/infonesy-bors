<?php

namespace B2\MyBB;

use \Respect\Relational\Sql;

class User extends Object
{
	function find_by_email_md5($email_md5, $data=[])
	{
		Connector::instance()->query('UPDATE mybb_users SET email_md5=MD5(email) WHERE email<>""');

		$user = new User(NULL);

		$map = $user->mapper->mybb_users(['email_md5' => $email_md5])->fetch();

		if($map)
			$user->data_map = $map;
		else
		{
			if(empty($data['username']))
				return NULL;

			$user->data_map->email_md5 = $email_md5;

			foreach($data as $p => $v)
				$user->data_map->$p = $v;
		}

		$user->save();

		return $user;
	}

	static function infonesy_import($data)
	{
		$user = User::find_by_email_md5($data['EmailMD5'], ['username' => $data['Title']]);
		$user->username = $data['Title'];

		if(($reg = strtotime($data['RegisterDate'])) && (!$user->regdate || $reg < $user->regdate))
			$user->regdate = $reg;

//		var_dump($data['RegisterDate'], $reg, $user->regdate);

		$user->lastvisit = strtotime($data['LastVisit']);

		$user->postnum = Connector::instance()->query('SELECT COUNT(*) FROM mybb_posts WHERE uid='.$user->uid())->fetchColumn(0);
		$user->threadnum = Connector::instance()->query('SELECT COUNT(*) FROM mybb_threads WHERE uid='.$user->uid())->fetchColumn(0);

		$last_post	= $user->mapper->mybb_posts(['uid' => $user->uid()])->fetch(Sql::orderBy('dateline DESC'));
		if($last_post)
			Connector::instance()->query('UPDATE mybb_users SET lastpost='.$last_post->dateline);

		$user->receivepms = 1;
		$user->allownotices = 1;
		$user->pmnotice = 1;
		$user->pmnotify = 1;
		$user->showimages = 1;
		$user->showvideos = 1;
		$user->showsigs = 1;
		$user->showavatars = 1;
		$user->showquickreply = 1;
		$user->showredirect = 1;

		$user->save();

		Bridge::cache_update_stats();
	}
}
