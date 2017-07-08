<?php

namespace Flarum;

class FlarumPasswordToken extends ObjectDb
{
	function table_name() { return 'flarum_password_tokens'; }

	function class_title() { return ec('Объект FlarumPasswordToken'); }
	function table_fields()
	{
		return [
			'id',
			'user_id',
			'created_at' => ['name' => 'UNIX_TIMESTAMP(`created_at`)'],
		];
	}
}
