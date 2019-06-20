<?php
/**
 * Для работы с данными
 */
class dataCore
{
	protected static $instance = null;

	protected function __construct()
	{
		# code...
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
}