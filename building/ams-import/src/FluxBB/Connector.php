<?php

namespace B2\FluxBB;

require_once __DIR__.'/../../config.php';

class Connector
{
	static function instance()
	{
		return $GLOBALS['fluxbb_connector'];
	}
}
