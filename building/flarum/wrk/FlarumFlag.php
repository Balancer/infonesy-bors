<?php

namespace Flarum;

class FlarumFlag extends ObjectDb
{
	function table_name() { return 'flarum_flags'; }

	function class_title() { return ec('Объект FlarumFlag'); }
	function table_fields()
	{
		return [
			'id',
			'post_id',
			'type',
			'user_id',
			'reason',
			'reason_detail',
			'time',
		];
	}
}
