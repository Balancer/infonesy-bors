<?php

namespace Flarum;

class FlarumUsersGroup extends ObjectDb
{
	function table_name() { return 'flarum_users_groups'; }

	function class_title() { return ec('Объект FlarumUsersGroup'); }
	function table_fields()
	{
		return [
			'user_id',
			'group_id',
		];
	}
}
