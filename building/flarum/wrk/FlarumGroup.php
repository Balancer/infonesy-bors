<?php

namespace Flarum;

class FlarumGroup extends ObjectDb
{
	function table_name() { return 'flarum_groups'; }

	function class_title() { return ec('Объект FlarumGroup'); }
	function table_fields()
	{
		return [
			'id',
			'name_singular',
			'name_plural',
			'color',
			'icon',
		];
	}
}
