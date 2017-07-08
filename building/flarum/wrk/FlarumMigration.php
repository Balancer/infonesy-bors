<?php

namespace Flarum;

class FlarumMigration extends ObjectDb
{
	function table_name() { return 'flarum_migrations'; }

	function class_title() { return ec('Объект FlarumMigration'); }
	function table_fields()
	{
		return [
			'migration',
			'extension',
		];
	}
}
