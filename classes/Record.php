<?php
/**
 * Class record
 *
 * @package moto-parser
 * @version 1.0
 */
class Record
{
    /**
     * @var array массив объектов полей
     */
    protected $fields = [];
    public function __construct(array $data=null)
    {
        foreach ($data as $name => $value) {
            $field = record_field::init($name, $value);
        }
    }
}
