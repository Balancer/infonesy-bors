<?php

namespace Flarum;

class FlarumNotification extends ObjectDb
{
	function table_name() { return 'flarum_notifications'; }

	function class_title() { return ec('Объект FlarumNotification'); }
	function table_fields()
	{
		return [
			'id',
			'user_id',
			'sender_id',
			'type',
			'subject_type',
			'subject_id',
			'data',
			'time',
			'is_read',
			'is_deleted',
		];
	}
}
