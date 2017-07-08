<?php

namespace Flarum;

class FlarumMentionsUser extends ObjectDb
{
	function table_name() { return 'flarum_mentions_users'; }

	function class_title() { return ec('Объект FlarumMentionsUser'); }
	function table_fields()
	{
		return [
			'post_id',
			'mentions_id',
		];
	}
}
