<?php

namespace B2\MyBB;

class Mapper
{
	static function factory()
	{
		$mapper = new \Respect\Relational\Mapper(Connector::instance());
		$mapper->setStyle(new DbIdStyle);
		return $mapper;
	}

	static function set_fields($object, $data, $map, $map_optional)
	{
		foreach($map as $db_property => $data_property)
			$object->$db_property = $data[$data_property];

		if(!$object->was_new)
			return;

		foreach($map_optional as $db_property => $data_property)
			$object->$db_property = $data[$data_property];
	}
}
