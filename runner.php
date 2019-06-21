<?php
/**
 * основной файл логики
 * 
 * @package moto-parser
 */

$data1 = [
	'host' => 'ftp.wpsstatic.com',
	'user' => 'wps',
	'pass' => 'WPSftp14',
];

$data = [
	'host' => 'ftp.helmethouse.com',
	'user' => 'datamart',
	'pass' => 'thebest',
	//'local_dir' => ROOT_DIR . DIRECTORY_SEPARATOR . 'download',
	'cur_files' => ['filelist.txt', 'Pricebook/changes1801.csv'], // пример нужных файлов
];

// ?action=addItem&host=ftp.wpsstatic.com&username=wps&password=WPSftp14
// ?action=addItem&host=ftp.helmethouse.com&username=datamart&password=thebest&path1=Pricebook/changes1801.csv

$core = dataCore::instance();

$core->set_data('test', [$data]);


echo '<pre>data: ';
echo "\n";
print_r($core->get_data('test'));
echo '</pre>';
exit;

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
