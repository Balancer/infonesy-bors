<?php

namespace Flarum;

class FlarumAuthToken extends ObjectDb
{
	function table_name() { return 'flarum_auth_tokens'; }

	function class_title() { return ec('Объект FlarumAuthToken'); }
	function table_fields()
	{
		return [
			'id',
			'payload',
			'created_at' => ['name' => 'UNIX_TIMESTAMP(`created_at`)'],
		];
	}
}
