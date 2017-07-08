<?php

namespace Flarum;

class FlarumPermission extends ObjectDb
{
	function table_name() { return 'flarum_permissions'; }

	function class_title() { return ec('Объект FlarumPermission'); }
	function table_fields()
	{
		return [
			'group_id',
			'permission',
		];
	}
}
