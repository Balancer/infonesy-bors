<?php

namespace Flarum;

class FlarumEmailToken extends ObjectDb
{
	function table_name() { return 'flarum_email_tokens'; }

	function class_title() { return ec('Объект FlarumEmailToken'); }
	function table_fields()
	{
		return [
			'id',
			'email',
			'user_id',
			'created_at' => ['name' => 'UNIX_TIMESTAMP(`created_at`)'],
		];
	}
}
