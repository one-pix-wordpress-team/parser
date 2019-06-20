<?php
/**
 * Для работы с данными
 * 
 * @package moto-parser
 * @version 1.0
 */
class dataCore
{
	protected static $instance = null;

	protected const DATA_DIR = 'data/';

	protected $options;

	protected function __construct()
	{
		$this->options = $this->get_data('option');
	}

	/**
	 * Синглтон
	 */
	static function instance()
	{
		if(empty(self::$instance))
			self::$instance = new self();

		return self::$instance;
	}

	/**
	 * Получение массива всех зарегистрированных подключений
	 */
	function get_connections()
	{
		# code...
	}

	/**
	 * Получение всех объектов указанного типа
	 * 
	 * @param $type string соответствующего типа
	 * @return array
	 */
	function get_all($type)
	{
		# code...
	}

	/**
	 * Создание объектов по переданным параметрам
	 * 
	 * @param $type string соответствующего типа
	 * @param $data array
	 * @return array
	 */
	function init_obj(string $type, array $data)
	{
		# code...
	}

	/**
	 * Получение информации объектов
	 * 
	 * @param $type string соответствующего типа
	 * @param $key string получение информации конкретного объекта по ключевому полю
	 * @return array
	 */
	function get_data(string $type, $key='')
	{
		$file = self::DATA_DIR . $type . '.csv';
		$csv = new CSV($file);
		$data = $csv->getCSV();
		if (!$data || count($data) < 2)
			return [];

		$row_keys = array_shift($data);

		if (!empty($key)) {
			$data = array_filter($data, function($d) use ($key) { // в идеале фильтрацию можно производить на уровне CSV объекта
				return $d[0] == $key;
			});
		}

		$data = array_map(function ($d) use ($row_keys) {
						return array_combine($row_keys, $d);
					}, $data);

		return $data;
	}

	/**
	 * Установка информации объектов
	 * 
	 * При совпадении ключевых полей информация будет перезаписана.
	 * 
	 * @todo Может возникнуть путаница, если поля во вложенных массивах сортированы поразному
	 * @param $type string соответствующего типа
	 * @param $data array
	 * @return bool
	 */
	function set_data(string $type, array $data)
	{
		if (empty($data))
			return false;

		$file = self::DATA_DIR . $type . '.csv';
		$csv = new CSV($file);

        array_unshift($data, array_keys($data[0]));

        try {
            $csv->setCSV($data, 'w');
        } catch (Exception $e) {
            return false;
        }
        return true;
	}

	/**
	 * Удаление информации объектов
	 * 
	 * @param $type string соответствующего типа
	 * @param $key string удаление информации конкретного объекта по ключевому полю
	 * @return array
	 */
	function del_data(string $type, $key)
	{
		# code...
	}

	/**
	 * лайтовый геттер
	 * 
	 * @return mixed
	 */
	function get($name) {
		return $this->$name;
	}
}