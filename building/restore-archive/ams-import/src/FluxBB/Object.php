<?php

use \Respect\Relational\Sql;

namespace B2\FluxBB;

class Object
{
	var $mapper;
	var $data_map;

	function class_name_p()
	{
		$name = strtolower(str_replace('B2\\FluxBB\\', '', get_called_class())); // '

		if(preg_match('/^(.+)y$/', $name, $m))
			return $m[1].'ies';

		return $name.'s';
	}

	function __construct($object_id)
	{
		$name_p = $this->class_name_p();

		$this->mapper = Mapper::factory();

		if($object_id)
		{
			$this->data_map = $this->mapper->$name_p($object_id)->fetch();

			if(!$this->data_map)
			{
//				throw new \Exception("Can't find {$name_p}({$object_id})");
			    $this->data_map = new \stdClass;
			    $this->data_map->id = $object_id;
			}
		}
		else
		{
		    $this->data_map = new \stdClass;
		    $this->data_map->id = NULL;
		}
	}

	function __call($method, $args)
	{
		if(preg_match('/^set_(\w+)$/', $method, $m))
		{
			$this->data_map->$m[1] = $args[0];
			return $this;
		}

		if(property_exists($this->data_map, $method))
			return $this->data_map->$method;

		throw new \Exception("Unknown method ".$method." for class ".get_class($this));
	}

	function __get($property)
	{
		if(property_exists($this->data_map, $property))
			return $this->data_map->$property;

		throw new \Exception("Unknown method ".$property." for class ".get_class($this));
	}

	function __set($property, $value)
	{
		$this->data_map->$property = $value;
	}

	function save()
	{
		$name_p = $this->class_name_p();
		$id = $this->id;
		$this->mapper->$name_p->persist($this->data_map);
//		echo "Flush for ".get_class($this)."\n";
//		print_r(debug_backtrace());
		$this->mapper->flush();
//		$this->data_map = $this->mapper->$name_p($id)->fetch();
	}
}
