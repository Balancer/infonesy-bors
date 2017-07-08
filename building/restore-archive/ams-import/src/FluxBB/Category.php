<?php

namespace B2\FluxBB;

use \Respect\Relational\Sql;

class Category extends Object
{
	static function find_by_uuid($category_uuid)
	{
		if(!$category_uuid)
			throw new \Exception("Empty category UUID");

		$category = new Category(NULL);

		$map = $category->mapper->categories(['uuid' => $category_uuid])->fetch();

		if($map)
			$category->data_map = $map;

		// Пробуем прописать оригинальный ID.
		if(preg_match('/^.+\.(\d+)$/', $category_uuid, $m))
		{
			$original_id = $m[1];
			$map = Mapper::factory()->categories($original_id)->fetch();

			// Если под оригинальным ID в нашей БД ничего нет, то удобно сохраниться под ним:
			if(!$map)
			{
				if($category->id)
					Connector::instance()->query('UPDATE categories SET id='.$original_id.' WHERE id='.$category->id);

				$category = new Category($original_id);
			}
		}

		$category->data_map->uuid = $category_uuid;

		if(empty($category->data_map->cat_name))
			$category->data_map->cat_name = '???';

		$category->disp_position	= 500;

		$category->save();

		return $category;
	}

	static function infonesy_import($data)
	{
		$category = category::find_by_uuid($data['UUID']);

		$category->uuid		= $data['UUID'];
		$category->cat_name	= $data['Title'];
		$category->disp_position	= 500;
		$category->save();

		$category->update();
	}

	function update()
	{
		$this->save();

		Connector::instance()->query('UPDATE forums SET cat_id='.$this->id().' WHERE category_uuid="'.$this->uuid().'"');
	}
}
