<?php

namespace Flarum;

class FlarumUsersDiscussion extends ObjectDb
{
	function table_name() { return 'flarum_users_discussions'; }

	function class_title() { return ec('Объект FlarumUsersDiscussion'); }
	function table_fields()
	{
		return [
			'user_id',
			'discussion_id',
			'read_time',
			'read_number',
			'subscription',
		];
	}
}
