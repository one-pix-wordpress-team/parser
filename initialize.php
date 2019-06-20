<?php
/**
 * Файл инициализации ядра
 * 
 * @package moto-parser
 */

/**
 * некоторые параметры конфигурации
 */
define('ROOT_DIR', __DIR__);

/**
 * автозагрузчик
 */
spl_autoload_register(function($class) {
    $file = ROOT_DIR . DIRECTORY_SEPARATOR . 'classes/' . $class . '.php';
    if (file_exists($file)) {
        include $file;
    }
}, false);

require_once 'inc/exceptions.php';
