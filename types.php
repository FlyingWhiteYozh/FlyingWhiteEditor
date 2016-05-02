<?php
namespace FWE;


class Field
{
	public $name, $callback, $value;
	public function __construct($name)
	{
		$this->name = $name;
	}

}

class Type
{
	public $name, $table, $id, $fields, $shouldInsert;

	public function __construct($name, $table, $id, $fields, $shouldInsert = false)
	{
		$this->name = $name;
		$this->table = $table;
		$this->fields = $fields;
		$this->id = $id;
		$this->shouldInsert = $shouldInsert;
	}
}

class Types
{
	public static function get($name)
	{
		$types = self::getAll();
		if(isset($types[$name])) return $types[$name];
		else return false;
	}

	public static function getAll()
	{
		return array
		(
			'product' => new Type (
				'product',
				's_products',
				array('id'),
				array(
					'meta_title' => new Field('Title'),
					'meta_description' => new Field('Description'),
					'meta_keywords' => new Field('Keywords'),
					'name' => new Field('Name'),
					'body' => new Field('Text'),
				)
			),

			'category' => new Type (
				'category',
				's_categories',
				array('id'),
				array(
					'meta_title' => new Field('Title'),
					'meta_description' => new Field('Description'),
					'meta_keywords' => new Field('Keywords'),
					'name' => new Field('Name'),
					'description' => new Field('Text'),
				)
			),

			'brand' => new Type (
				'brand',
				's_brands',
				array('id'),
				array(
					'meta_title' => new Field('Title'),
					'meta_description' => new Field('Description'),
					'meta_keywords' => new Field('Keywords'),
					'name' => new Field('Name'),
					'description' => new Field('Text'),
				)
			),

			'page' => new Type (
				'page',
				's_pages',
				array('id'),
				array(
					'meta_title' => new Field('Title'),
					'meta_description' => new Field('Description'),
					'meta_keywords' => new Field('Keywords'),
					'name' => new Field('Name'),
					'body' => new Field('Text'),
				)
			),

			'post' => new Type (
				'post',
				's_blog',
				array('id'),
				array(
					'meta_title' => new Field('Title'),
					'meta_description' => new Field('Description'),
					'meta_keywords' => new Field('Keywords'),
					'name' => new Field('Name'),
					'text' => new Field('Text'),
					'annotation' => new Field('Annotation'),
				)
			),

			'brand_category' => new Type (
				'brand_category',
				's_brands_categories',
				array('brand', 'category'),
				array(
					'meta_title' => new Field('Title'),
					'meta_description' => new Field('Description'),
					'meta_keywords' => new Field('Keywords'),
					'name' => new Field('Name'),
					'description' => new Field('Text'),
				),
				true
			),
		);
	}
}
