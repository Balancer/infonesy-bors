<?php

namespace B2\MyBB;

class Bridge
{
	static function cache_update_forums()
	{
		$GLOBALS['cache']->update_forums();
	}

	static function cache_update_stats()
	{
		$GLOBALS['cache']->update_stats();
	}
}
