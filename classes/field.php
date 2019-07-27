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
		'simple_dynamic',// регулярно обновляемое значение (Если уже есть, то перезаписывает старое значение)
		'simple_static', // значение не изменяется на регулярной основе (Пропускается, если уже есть)
		'key',           // ключевое поле (Используется для поиска похожей записи)
        'by_realer',     // поле зависящее от поставщика
	];

	/**
	 * Данные зарегистрированных полей
	 */
    const AVAILABLE_FIELDS = [
        [
            'type'  => 0,
            'name'  => 'Vendor',
        ],
        [
            'type'  => 0,
            'name'  => 'Vendor part number ',
        ],
        [
            'type'  => 0,
            'name'  => 'Vendor punctuated part number ',
        ],
        [
            'type'  => 2,
            'name'  => 'UPC',
        ],
        [
            'type'  => 3,
            'name'  => 'Dist 1 part num',
        ],
        [
            'type'  => 3,
            'name'  => 'Dist 1 punctuated part num',
        ],
        [
            'type'  => 0,
            'name'  => 'Description',
        ],
        [
            'type'  => 0,
            'name'  => 'Long Description',
        ],
        [
            'type'  => 0,
            'name'  => 'Model',
        ],
        [
            'type'  => 0,
            'name'  => 'Size',
        ],
        [
            'type'  => 0,
            'name'  => 'Color',
        ],
        [
            'type'  => 3,
            'name'  => 'Dist 1 dealer price',
        ],
        [
            'type'  => 0,
            'name'  => 'Retail price',
        ],
        [
            'type'  => 3,
            'name'  => 'Dist 1 Your price',
        ],
        [
            'type'  => 3,
            'name'  => 'Dist 1 warehouse',
        ],
        [
            'type'  => 0,
            'name'  => 'Status',
        ],
        [
            'type'  => 0,
            'name'  => 'MAP Y/N',
        ],
        [
            'type'  => 0,
            'name'  => 'MAP Price',
        ],
        [
            'type'  => 0,
            'name'  => 'Weight',
        ],
        [
            'type'  => 0,
            'name'  => 'Length',
        ],
        [
            'type'  => 0,
            'name'  => 'Width',
        ],
        [
            'type'  => 0,
            'name'  => 'Depth',
        ],
        [
            'type'  => 3,
            'name'  => 'Kit Item',
        ],
        [
            'type'  => 3,
            'name'  => 'Unit of Measure',
        ],
        [
            'type'  => 3,
            'name'  => 'Truck Part Only',
        ],
        [
            'type'  => 3,
            'name'  => 'Hazardous',
        ],
        [
            'type'  => 3,
            'name'  => 'Photo',
        ],
        [
            'type'  => 3,
            'name'  => 'Apparel',
        ],
        [
            'type'  => 3,
            'name'  => 'Street',
        ],
        [
            'type'  => 3,
            'name'  => 'Offroad',
        ],
        [
            'type'  => 3,
            'name'  => 'Snowmobile',
        ],
        [
            'type'  => 3,
            'name'  => 'ATV',
        ],
        [
            'type'  => 3,
            'name'  => 'Watercraft',
        ],
        [
            'type'  => 3,
            'name'  => 'VTwin',
        ],
        [
            'type'  => 3,
            'name'  => 'Tire',
        ],
    ];
//	const AVAILABLE_FIELDS = [
//		[
//			'type'  => 2,
//			'name'  => 'Part Number',
//			'regex' => '',
//		],
//		[
//			'type'  => 0,
//			'name'  => 'Alt Part#',
//			'regex' => '',
//		],
//		[
//			'type'  => 0,
//			'name'  => 'Description',
//			'regex' => '',
//		],
//		[
//			'type'  => 3,
//			'name'  => 'Dealer',
//			'regex' => '',
//		],
//		[
//			'type'  => 3,
//			'name'  => 'Retail',
//			'regex' => '',
//		],
//		[
//			'type'  => 3,
//			'name'  => 'West',
//			'regex' => '',
//		],
//		[
//			'type'  => 3,
//			'name'  => 'East',
//			'regex' => '',
//		],
//		[
//			'type'  => 3,
//			'name'  => 'Status',
//			'regex' => '',
//		],
//		[
//			'type'  => 3,
//			'name'  => 'MAPP Y/N',
//			'regex' => '',
//		],
//		[
//			'type'  => 3,
//			'name'  => 'MAPP Price',
//			'regex' => '',
//		],
//		[
//			'type'  => 0,
//			'name'  => 'Weight (Oz)',
//			'regex' => '',
//		],
//		[
//			'type'  => 0,
//			'name'  => 'Length',
//			'regex' => '',
//		],
//		[
//			'type'  => 0,
//			'name'  => 'Width',
//			'regex' => '',
//		],
//		[
//			'type'  => 0,
//			'name'  => 'Depth',
//			'regex' => '',
//		],
//		[
//			'type'  => 0,
//			'name'  => 'Long Description',
//			'regex' => '',
//		],
//		[
//			'type'  => 1,
//			'name'  => 'Brand',
//			'regex' => '',
//		],
//		[
//			'type'  => 1,
//			'name'  => 'Photo',
//			'regex' => '',
//		],
//		[
//			'type'  => 2,
//			'name'  => 'UPC',
//			'regex' => '',
//		],
//		[
//			'type'  => 3,
//			'name'  => 'Catalog Page',
//			'regex' => '',
//		],
//		[
//			'type'  => 0,
//			'name'  => 'Size',
//			'regex' => '',
//		],
//		[
//			'type'  => 3,
//			'name'  => 'Color',
//			'regex' => '',
//		],
//		[
//			'type'  => 0,
//			'name'  => 'Model',
//			'regex' => '',
//		],
//		[
//			'type'  => 0,
//			'name'  => 'Origin',
//			'regex' => '',
//		],
//		[
//			'type'  => 1,
//			'name'  => 'Alt Photos',
//			'regex' => '',
//		],
//		[
//			'type'  => 1,
//			'name'  => 'Category',
//			'regex' => '',
//		],
//		[
//			'type'  => 1,
//			'name'  => 'Class',
//			'regex' => '',
//		],
//		[
//			'type'  => 3,
//			'name'  => 'TTL Qty',
//			'regex' => '',
//		],
//		[
//			'type'  => 3,
//			'name'  => 'Feature Text File',
//			'regex' => '',
//		],
//	];

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
     * @param array $available_fields производить поиск по собственному списку полей
     * @return int|false ключ зарегистрированного поля
	 */
	static function is_field($name, $available_fields=null)
	{
        $available_fields = $available_fields ?? self::AVAILABLE_FIELDS;
		foreach ($available_fields as $field => $value)
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
