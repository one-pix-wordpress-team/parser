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
		$data = [
			'host' => $_POST['host'],
			'user' => $_POST['username'],
			'pass' => $_POST['password'],
		];
		if (!empty($_POST['path1']))
			$data['cur_files'][] = $_POST['path1'];
		if (!empty($_POST['path2']))
			$data['cur_files'][] = $_POST['path2'];
		if (!empty($_POST['path3']))
			$data['cur_files'][] = $_POST['path3'];

		$c = connection::init($data);
		if ($c->get('status') == 'successfully connected') { // проверка подключения
			$core = dataCore::instance();
            $core->save_obj($c);
			echo 'true';
		} else {
			echo 'false';
		}
		break;

	case 'deleteItem':
		if (!empty($_POST['host'])) {
			$core = dataCore::instance();
			$core->del_data('connection', $_POST['host']); // удаление подключения
			echo 'true';
		} else {
			echo 'false';
		}
		break;
	
	default:
		echo 'action is not correct';
		break;
}

die();
