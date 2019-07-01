<?php
/**
 * Обработчики внешних запросов
 * 
 * @package moto-parser
 * @version 1.0
 */


add_action( 'wp_ajax_moto_parser', 'moto_parser_handler' );
//add_action( 'wp_ajax_nopriv_moto_parser', 'moto_parser_handler' );

function moto_parser_handler()
{
    if (empty($_POST['pAction']))
        die('go away!');

    switch ($_POST['pAction']) {
        case 'addItem':
            $data = [
                'host' => $_POST['host'],
                'user' => $_POST['username'],
                'pass' => $_POST['password'],
            ];
            // $data['cur_files'][] = $_POST['path']; // добавление файлов

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
                echo $core->del_data('connection', $_POST['host']) ? 'true' : 'false'; // удаление подключения
            } else {
                echo 'false';
            }
            break;

        case 'getFiles':
            if (!empty($_POST['host'])) {
                $core = dataCore::instance();
                $c = $core->get_all('connection', $_POST['host']);
                $c = array_shift($c);
                if ($c) {
                    $files = $c->get_files_list();
                    echo json_encode($files);
                } else {
                    echo 'host err';
                }
            } else {
                echo 'host is empty';
            }
            break;

        case 'acceptFiles':
            if (!empty($_POST['host'])) {
                $files = json_decode(stripcslashes($_POST['files']), true);
                if (null === $files) {
                    echo 'files err';
                    break;
                }
                $files = $files ? array_keys($files) : [];
                $core = dataCore::instance();
                $c = $core->get_all('connection', $_POST['host']);
                $c = array_shift($c);
                if ($c) {
                    $c->set_cur_files($files);
                    echo $core->save_obj($c) ? 'true' : 'false';
                } else {
                    echo 'host err';
                }
            } else {
                echo 'host is empty';
            }
            break;

        case 'loadAndCombine':
            $core = dataCore::instance();
            $connections = $core->get_all('connection');
            $files = [];
            foreach ($connections as $connection) {
                $files[] = $connection->get_files();
            }

            if (isset($files[0][0])) {
                preg_match('@(/download/.*)@', $files[0][0], $m);
                print_r($m[1]);
            } else {
                echo 'false';
            }
            break;

        default:
            echo 'action is not correct';
            break;
    }

    wp_die();
}
