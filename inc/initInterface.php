<?php
/**
 * Интерфейс иннициализации объектов класса
 *
 * @package moto-parser
 */
interface initInterface {
	public static function init($data);
	public function data_to_save();
}
