<?php

namespace Flarum;

class FlarumAccessToken extends ObjectDb
{
	function table_name() { return 'flarum_access_tokens'; }

	function class_title() { return ec('Объект FlarumAccessToken'); }
	function table_fields()
	{
		return [
			'id',
			'user_id',
			'last_activity',
			'lifetime',
		];
	}
}
