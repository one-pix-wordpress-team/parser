<?php
/**
 * основной файл логики
 * 
 * @package moto-parser
 */

$data = [
	'host' => 'ftp.wpsstatic.com',
	'user' => 'wps',
	'pass' => 'WPSftp14',
];

$data = [
	'host' => 'ftp.helmethouse.com',
	'user' => 'datamart',
	'pass' => 'thebest',
	'local_dir' => ROOT_DIR . DIRECTORY_SEPARATOR . 'download',
	'cur_files' => ['filelist.txt', 'Pricebook/changes1801.csv'], // пример нужных файлов
];

$c = connection::init($data);

echo '<pre>status: ';
print_r($c->get('status'));
echo "\n";
echo "files:\n";
print_r($c->get('cur_files'));
echo "\n";
echo "files in Pricebook:\n";
print_r( $c->get_files_list('Pricebook') );

$c->get_files(); // скачать нужные файлы
echo "\n";
echo "files:\n";
print_r($c->get('cur_files'));

// echo "\n";
// echo 'host: ' . $c->get('host') . "\n";
// echo 'user: ' . $c->get('user') . "\n";
// echo 'pass: ' . $c->get('pass') . "\n";

// var_dump(ftp_connect('ftp.wpsstatic.com'));
echo '</pre>';
