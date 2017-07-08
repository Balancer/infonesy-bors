<?php

namespace Flarum;

class FlarumPostsLike extends ObjectDb
{
	function table_name() { return 'flarum_posts_likes'; }

	function class_title() { return ec('Объект FlarumPostsLike'); }
	function table_fields()
	{
		return [
			'post_id',
			'user_id',
		];
	}
}
