<?php
/**
 * Файл инициализации ядра
 * 
 * @package moto-parser
 */

/**
 * Вывод предупреждений и ошибок
 */
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

/**
 * некоторые параметры конфигурации
 */
define('ROOT_DIR', __DIR__);

/**
 * автозагрузчик
 */
spl_autoload_register(function($class) {
    $file = ROOT_DIR . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $class . '.php';
    if (file_exists($file)) {
        include $file;
    }
}, false);

require_once 'inc/exceptions.php';
require_once 'inc/initInterface.php';
require_once 'handler.php';
