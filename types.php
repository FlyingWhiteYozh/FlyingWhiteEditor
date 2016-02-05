<?php
namespace FWE;

if(!class_exists('Field')) {
	class Field 
	{
		public $name, $callback, $value;
		public function __construct($name, $callback = NULL)
		{
			$this->name = $name;
			$this->callback = $callback;
		}

		private function getValue()
		{
			if($name == 'Keywords') $value = mb_strtolower($value);
			if(is_callable($callback)) return $callback($value);
			else return $value;
		}

	}
}
if(!class_exists('Type')) {
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
}
return array(
	'product' => new Type (
		'product',
		's_products',
		array('id'),
		array(
			'meta_title' => new Field('Title'),
			'meta_description' => new Field('Description'),
			'meta_keywords' => new Field('Keywords'/*, function ($value) { return mb_strtolower($value); }*/),
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