<?php

namespace B2\MyBB;

require_once __DIR__.'/../../config.php';

class Connector
{
	static function instance()
	{
		return $GLOBALS['mybb_connector'];
	}
}
