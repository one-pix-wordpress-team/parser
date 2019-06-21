<?php
/**
 * Интерфейс иннициализации объектов класса
 */
interface initInterface {
	public static function init($data);
	public function data_to_save();
}