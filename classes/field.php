<?php
/**
 * Распознаваемое поле (атрибут) товара
 * 
 * @package moto-parser
 * @version 1.0.1
 */
class field
{
	/**
	 * @var int ключ поля в массиве AVAILABLE_FIELDS
	 */
	protected $field;

	/**
	 * @var string значение поля
	 */
	protected $value;

	/**
	 * Интерпретация типов
	 */
	const TYPES = [
		'dynamic', // регулярно обновляемое значение
		'static',  // значение не изменяется на регулярной основе
		'key',     // ключевое поле
	];

	/**
	 * Данные зарегистрированных полей
	 */
	const AVAILABLE_FIELDS = [
		[
			'type'  => 2,
			'name'  => 'Part Number',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Alt Part#',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Description',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Dealer',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Retail',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'West',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'East',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Status',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'MAPP Y/N',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'MAPP Price',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Weight (Oz)',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Length',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Width',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Depth',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Long Description',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Brand',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Photo',
			'regex' => '',
		],
		[
			'type'  => 2,
			'name'  => 'UPC',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Catalog Page',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Size',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Color',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Model',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Origin',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Alt Photos',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Category',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Class',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'TTL Qty',
			'regex' => '',
		],
		[
			'type'  => 0,
			'name'  => 'Feature Text File',
			'regex' => '',
		],
	];

	function __construct($data)
	{
		$this->field = $data['field'];
		$this->value = $data['value'];
	}

	/**
	 * Инициализация поля
	 * 
	 * @param array $data
	 * @return object|false
	 */
	static function init($data)
	{
		if(!empty($data['field'])) {
			return new field($data);
		} elseif (!empty($data['name'])) {
			if ($field = self::is_field($data['name']) !== false) {
				$data['field'] = $field;
				return new field($data);
			}
		}

		return false;
	}

	/**
	 * Проверить зарегистрировано ли поле
	 * 
	 * @param string $name название поля
	 * @return int|false ключ зарегистрированного поля
	 */
	static function is_field($name)
	{
		foreach (self::AVAILABLE_FIELDS as $field => $value)
			if(stripos($name, $value['name']) === 0)
				return $field;

		return false;
	}

	/**
	 * лайтовый геттер
	 * 
	 * @param string $name
	 * @return mixed
	 */
	function get($name)
	{
		return $this->$name;
	}
}
