<?php

namespace B2\FluxBB;

class User extends Object
{
	function find_by_email_md5($email_md5, $data=[])
	{
		Connector::instance()->query('UPDATE users SET email_md5=MD5(email) WHERE email<>""');

		$user = new User(NULL);

		$map = $user->mapper->users(['email_md5' => $email_md5])->fetch();

		if($map)
			$user->data_map = $map;
		else
		{
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
		$user->registered = strtotime($data['RegisterDate']);
		$user->last_visit = strtotime($data['LastVisit']);

		$user->save();
	}
}
