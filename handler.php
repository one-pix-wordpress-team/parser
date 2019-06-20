<?php
/**
 * Обработчики внешних запросов
 * 
 * @package moto-parser
 * @version 1.0
 */

require_once 'initialize.php';

if(empty($_POST['action']))
	die('go away!');

switch ($_POST['action']) {
	case 'addItem':
		# code...
		break;

	case 'deleteItem':
		# code...
		break;
	
	default:
		# code...
		break;
}
