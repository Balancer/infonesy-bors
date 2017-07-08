<?php

namespace Flarum;

class FlarumMentionsPost extends ObjectDb
{
	function table_name() { return 'flarum_mentions_posts'; }

	function class_title() { return ec('Объект FlarumMentionsPost'); }
	function table_fields()
	{
		return [
			'post_id',
			'mentions_id',
		];
	}
}
