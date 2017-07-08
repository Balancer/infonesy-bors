<?php

namespace Flarum;

class FlarumUsersTag extends ObjectDb
{
	function table_name() { return 'flarum_users_tags'; }

	function class_title() { return ec('Объект FlarumUsersTag'); }
	function table_fields()
	{
		return [
			'user_id',
			'tag_id',
			'read_time',
			'is_hidden',
		];
	}
}
