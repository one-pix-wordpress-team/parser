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
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
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
                    $c->set_cur_files($files); // добавляем список файлов
                    $files = $c->get_files(); // список скачанных файлов

                    $headers = [];
                    foreach ($files as $file) {
                        $csv = new CSV($file);
                        $h = $csv->getCSV(',');
                        if (count($h) < 1)
                            continue;
                        $h = array_shift($h);
                        if (is_array($h))
                            $headers = array_merge($headers, $h);
                    }
                    $headers = array_unique($headers);
                    $h = array_map(function ($f){
                        return $f['name'];
                    }, RecordField::AVAILABLE_FIELDS);
//                    }, field::AVAILABLE_FIELDS);
                    $resp = ['static_fields' => $h, 'files_fields' => $headers];

                    if ($core->save_obj($c)) {
                        echo json_encode($resp);
                    } else {
                        echo 'false';
                    }
                } else {
                    echo 'host err';
                }
            } else {
                echo 'host is empty';
            }
            break;

        case 'setFilesFields': // добавление соотношений для полей файлов
            if (!empty($_POST['host'])) {
                if (isset($_POST['files_fields'])) { // здесь должен быть массив соотношений
                    $files_fields = json_decode(stripcslashes($_POST['files_fields']), true);
                    if (null === $files_fields || !is_array($files_fields)) {
                        echo 'files fields err';
                        break;
                    }
                    $core = dataCore::instance();
                    $c = $core->get_all('connection', $_POST['host']);
                    $c = array_shift($c);
                    $c->set('files_fields', $files_fields);
                    echo $core->save_obj($c) ? 'true' : 'false';
                } else {
                    echo 'files fields is not exist';
                }
            } else {
                echo 'host is empty';
            }
            break;

        case 'loadAndCombine':
            $ex = new RecordsExport;
            $ex->run();
            $ex->put_csv('/var/www/admin/data/www/parser.one-pix.com/wp-content/plugins/moto-parser/download/main.csv');
            echo '/wp-content/plugins/moto-parser/download/main.csv';
            break;

        case 'updateRecords':

//            ini_set('memory_limit', '1024M');
            $core = dataCore::instance();
            $connections = $core->get_all('connection');

            // получение файлов с подключений ftp
            $files = [];
            foreach ($connections as $c) {
                $new_files = $c->get_files(); // массив загруженных файлов
                if ($new_files) {
                    $files = array_merge($files, $new_files);
                }
            }
            unset($connections);

//            $files = [];
            $pu = Partsnetweb::init();
            if ($pu) {
                $file = $pu->getCSVFile();
                if ($file)
                    $files[] = $file;
            }
            $files_fields = [
                'Brand Name' => 'Vendor',
                'Vendor Part Number' => 'Vendor part number',
                'Vendor Punctuated Part Number' => 'Vendor punctuated part number',
                'UPC Code' => 'UPC',
                'Part Number' => 'Dist part num', // (1)
                'Punctuated Part Number' => 'Dist punctuated part num', // (1)
                'Part Description' => 'Description',
                'Base Dealer Price' => 'Dist dealer price', // (1)
                'Original Retail' => 'Retail price',
                'Your Dealer Price' => 'Dist Your price', // (1)
                'Part Status' => 'Status',
                'Weight' => 'Weight',
            ];
            $header_rules = $core->get_cash('header_rules');
            if (isset($header_rules) && is_array($header_rules))
                $header_rules = array_merge($header_rules, $files_fields);
            else
                $header_rules = $files_fields;
            $core->set_cash('header_rules', $header_rules);
//            die(print_r($files, true));

            // обход файлов, порсинг данных
            $p = new bd_data_parser($files);
            $p->run();
//            print_r($p);
            echo 'true';
            break;

        case 'partsUnlimited':
            if (!empty($_POST['access'])) { // здесь должен быть массив с доступами
                parse_str($_POST['access'], $access);
                if (empty($access['dist_id']) ||
                    empty($access['dist_user']) ||
                    empty($access['dist_password'])) {
                    echo 'access is not full';
                    break;
                }

                $data = [
                    'dist_id' => $access['dist_id'],
                    'dist_user' => $access['dist_user'],
                    'dist_password'=> $access['dist_password']
                ];

                $core = dataCore::instance();
                echo $core->set_data('partsUnlimited', [$data]) ? 'true' : 'false';
            } else {
                echo 'access is empty';
            }
            break;

        case 'test':
            include 'runner.php';
            break;

        default:
            echo 'action is not correct';
            break;
    }

    wp_die();
}
