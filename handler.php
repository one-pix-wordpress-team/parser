<?php
/**
 * Обработчики внешних запросов
 * 
 * @package moto-parser
 */

require_once 'initialize.php';

if(empty($_POST['action']))
	die('go away!');

switch ($_POST['action']) {
	case 'add':
		# code...
		break;

	case 'remove':
		# code...
		break;

	case 'get':
		# code...
		break;
	
	default:
		# code...
		break;
}
