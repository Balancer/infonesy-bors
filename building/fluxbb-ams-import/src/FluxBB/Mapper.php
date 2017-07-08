<?php

namespace B2\FluxBB;

class Mapper
{
	static function factory()
	{
		return new \Respect\Relational\Mapper(Connector::instance());
	}
}
