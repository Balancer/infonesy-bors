<?php

namespace Flarum;

class FlarumApiKey extends ObjectDb
{
	function table_name() { return 'flarum_api_keys'; }

	function class_title() { return ec('Объект FlarumApiKey'); }
	function table_fields()
	{
		return [
			'id',
		];
	}
}
