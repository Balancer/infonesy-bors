<?php

namespace Flarum;

class FlarumSetting extends ObjectDb
{
	function table_name() { return 'flarum_settings'; }

	function class_title() { return ec('Объект FlarumSetting'); }
	function table_fields()
	{
		return [
			'key',
			'value' => ['type' => 'bbcode'],
		];
	}
}
