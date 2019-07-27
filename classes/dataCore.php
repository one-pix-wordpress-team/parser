<?php
/**
 * Для работы с данными
 * 
 * @package moto-parser
 * @version 1.4
 */
class dataCore
{
	protected static $instance = null;

	protected const DATA_DIR = 'data' . DIRECTORY_SEPARATOR;

	protected $options = [
		'download_dir' => ROOT_DIR . DIRECTORY_SEPARATOR . 'download',
	];

    protected $cash;

    protected function __construct()
	{
		$options = $this->get_data('option');
		$this->options = array_merge($this->options, $options);
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
     * @param string $name
     * @param $value
     */
	function set_cash(string $name, $value)
    {
        $this->cash[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    function get_cash(string $name)
    {
        return $this->cash[$name] ?? null;
    }

	/**
	 * Получить значение опции
	 * 
	 * @param $name string
	 * @return mixed
	 */
	function get_option(string $name='') {
		if (empty($name)) {
			return $this->options;
		}
		return $this->options[$name] ?? null;
	}

	/**
	 * Задать значение опции
	 * 
	 * @param $name string
	 * @param $value mixed
	 */
	function set_option(string $name, $value) {
		$this->options[$name] = $value;
	}

	/**
	 * Сохранение данных объектов
	 * 
	 * @param $obj object
	 * @return bool
	 */
	function save_obj($obj) {
		if (!($obj instanceof initInterface))
			return false;
		
		$type = get_class($obj);
		$data = $obj->data_to_save();
		return $this->add_data($type, $data);
	}

	/**
	 * Получение всех объектов указанного типа
	 * 
	 * @param $type string соответствующего типа
	 * @param $key string получение конкретного объекта по ключевому полю
	 * @return array
	 */
	function get_all(string $type, $key='')
	{
		$all_data = $this->get_data($type, $key);
        $objs = [];
		foreach ($all_data as $data) {
			$objs[] = $this->init_obj($type, $data);
		}
		
		return $objs;
	}

	/**
	 * Создание объектов по переданным параметрам
	 * 
	 * @todo выкинуть бы исключение в случае если указанный класс не корректный
	 * @param $type string соответствующего типа
	 * @param $data array
	 * @return array|false
	 */
	function init_obj(string $type, array $data)
	{
		if (!is_subclass_of($type, 'initInterface'))
			return false;

		return $type::init($data);
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
		$file = ROOT_DIR . DIRECTORY_SEPARATOR . self::DATA_DIR . $type . '.csv';
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
		if (!empty($data)) {
			array_unshift($data, array_keys(reset($data)));
		}

		$file = ROOT_DIR . DIRECTORY_SEPARATOR . self::DATA_DIR . $type . '.csv';
		$csv = new CSV($file);

        try {
            $csv->setCSV($data, 'w');
        } catch (Exception $e) {
            return false;
        }
        return true;
	}

	/**
	 * Добавить данные об объекте к имеющимся
	 * 
	 * @param $type string соответствующего типа
	 * @param $new_data array
	 * @return bool
	 */
	function add_data(string $type, array $new_data)
	{
		$data = $this->get_data($type);

		$k = reset($new_data); // ключевое поле нового массива

		// фильтруем данные, чтобы в файле была только одна строка с ключевым полем
		// ключевым считается первое поле в строке
		$data = array_filter($data, function($d) use ($k) {
			return reset($d) !== $k;
		});
		$data[] = $new_data;
		
		return $this->set_data($type, $data);
	}

	/**
	 * Удаление информации объектов
	 * 
	 * @param $type string соответствующего типа
	 * @param $key string удаление информации конкретного объекта по ключевому полю
	 * @return array|bool
	 */
	function del_data(string $type, string $key)
	{
		$data = $this->get_data($type);

		// фильтруем данные, удаляя поле с ключевым полем равным указанному
		// ключевым считается первое поле в строке
		$data = array_filter($data, function($d) use ($key) {
			return reset($d) !== $key;
		});

		return $this->set_data($type, $data);
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
