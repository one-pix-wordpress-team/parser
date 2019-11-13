<?php
/**
 * Class RecordField
 *
 * @package moto-parser
 * @version 1.0
 */
class RecordField
{
    /**
     * Подтвержденные поля
     *
     * типы:
     * 1 - ключевое поле
     * 2 - простое обновляемое
     * 3 - простое не обновляемое
     * 4 - обновляемое, зависимое от дистрибьютора
     */
    const AVAILABLE_FIELDS = [
        [
            'name' => 'Vendor',
            'type' => '2',
        ],
        [
            'name' => 'Vendor part number',
            'type' => '2',
        ],
        [
            'name' => 'Vendor punctuated part number',
            'type' => '2',
        ],
        [
            'name' => 'UPC',
            'type' => '1',
        ],
        [
            'name' => 'Dist part num', // (1)
            'type' => '4',
        ],
        [
            'name' => 'Dist punctuated part num', // (1)
            'type' => '4',
        ],
        [
            'name' => 'Description',
            'type' => '3',
        ],
        [
            'name' => 'Long Description',
            'type' => '3',
        ],
        [
            'name' => 'Model',
            'type' => '3',
        ],
        [
            'name' => 'Size',
            'type' => '3',
        ],
        [
            'name' => 'Color',
            'type' => '3',
        ],
        [
            'name' => 'Dist dealer price', // (1)
            'type' => '4',
        ],
        [
            'name' => 'Retail price',
            'type' => '2',
        ],
        [
            'name' => 'Dist Your price', // (1)
            'type' => '4',
        ],
        [
            'name' => 'Dist warehouse', // (1)
            'type' => '4',
        ],
        [
            'name' => 'Status',
            'type' => '2',
        ],
        [
            'name' => 'MAP Y/N',
            'type' => '2',
        ],
        [
            'name' => 'MAP Price',
            'type' => '2',
        ],
        [
            'name' => 'Weight',
            'type' => '3',
        ],
        [
            'name' => 'Length',
            'type' => '3',
        ],
        [
            'name' => 'Width',
            'type' => '3',
        ],
        [
            'name' => 'Depth',
            'type' => '3',
        ],
        [
            'name' => 'Kit Item',
            'type' => '3',
        ],
        [
            'name' => 'Unit of Measure',
            'type' => '3',
        ],
        [
            'name' => 'Truck Part Only',
            'type' => '2',
        ],
        [
            'name' => 'Hazardous',
            'type' => '3',
        ],
        [
            'name' => 'Photo',
            'type' => '3',
        ],
        [
            'name' => 'Apparel',
            'type' => '3',
        ],
        [
            'name' => 'Street',
            'type' => '2',
        ],
        [
            'name' => 'Offroad',
            'type' => '2',
        ],
        [
            'name' => 'Snowmobile',
            'type' => '2',
        ],
        [
            'name' => 'ATV',
            'type' => '2',
        ],
        [
            'name' => 'Watercraft',
            'type' => '2',
        ],
        [
            'name' => 'VTwin',
            'type' => '2',
        ],
        [
            'name' => 'Tire',
            'type' => '2',
        ],
    ];

    /**
     * @var string отпределяет как нужно обрабатывать данное поле.
     */
    protected $type;

    /**
     * @var string одобренное название поля
     */
    protected $name;

    /**
     * @var string значение
     */
    protected $value;

    /**
     * Исчточник значения.
     *
     * Обычно - название дестрибьютора
     *
     * @var string
     */
    protected $source;

    protected function __construct($field)
    {
        $this->name = $field['name'];
        $this->type = $field['type'];
        $this->source = $field['source'];
    }

    /**
     * Определяет поле по названию
     *
     * @param string $name
     * @return array|false
     */
    static function isField(string $name)
    {
        foreach (self::AVAILABLE_FIELDS as $f) {
            if ($f['name'] === $name)
                return $f;
        }
        return false;
    }

    /**
     * Инициализирует объект поля по переданному названию
     *
     * @param string $name
     * @param string $source
     * @return false|RecordField
     */
    static function init(string $name, $source = '')
    {
        $f = self::isField($name);
        if (false !== $f) {
            $f['source'] = $source;
            return new self($f);
        }
        return false;
    }

    /**
     * @param string $value значение поля
     */
    function setup($value)
    {
        $this->value = $value;
    }

    /**
     * Обновляет поле
     *
     * @param string $post_id ID записи для которой обновляется данное поле
     * @return mixed
     */
    function update($post_id)
    {
        $result = null;
        switch ($this->type) {
            case '4':
                $key = str_replace(' ', '_', strtolower($this->name)) . ':' . $this->source;
                $result = update_post_meta($post_id, $key, $this->value);
                break;
            case '1':
            case '2':
            case '3':
            default:
                $key = str_replace(' ', '_', strtolower($this->name));
                $result = update_post_meta($post_id, $key, $this->value);
        }
        return $result;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
