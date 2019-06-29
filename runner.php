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
	'cur_files' => ['Pricing/WPS_Daily_Combined.csv'],
];

$data = [
	'host' => 'ftp.helmethouse.com',
	'user' => 'datamart',
	'pass' => 'thebest',
	//'local_dir' => ROOT_DIR . DIRECTORY_SEPARATOR . 'download',
	'cur_files' => ['Pricebook/master.csv'], // пример нужных файлов
];
$file = dataCore::instance()->get_option('download_dir') . DIRECTORY_SEPARATOR . 'ftp.helmethouse.com' . DIRECTORY_SEPARATOR . 'master.csv';
// ?action=addItem&host=ftp.wpsstatic.com&username=wps&password=WPSftp14
// ?action=addItem&host=ftp.helmethouse.com&username=datamart&password=thebest&path1=Pricebook/changes1801.csv
// $c = connection::init($data);
// var_dump($file);
$cp = new csv_parser($file);
$cp->headers();
$d = $cp->content();
echo '<pre>';
print_r($d);
echo '</pre>';
exit;

$csv = new CSV($file);
$content = $csv->getCSV(',');
// exit;

$fs = [
	'Part Number',
	'Alt Part#',
	'Description',
	'Dealer',
	'rEtail',
	'WEST',
	'East',
	'Status',
	'MAPP Y/N',
	'MAPP Price',
	'Weight',
	'Length',
	'Width',
	'Писка'
];
foreach ($content[0] as $name) {
	if (($f = field::is_field($name)) !== false) {
		echo "<p style=\"color:blue\">$f</p>";
	} else {
		echo "<p style=\"color:red\">NO</p>";
	}
}
exit;

// $core = dataCore::instance();
// $connections = $core->get_all('connection');
$file = dataCore::instance()->get_option('download_dir') . DIRECTORY_SEPARATOR . 'ftp.helmethouse.com' . DIRECTORY_SEPARATOR . 'master.csv';

echo '<pre>connections: ';
	echo "\n";
foreach ($connections as $connection) {
	echo $connection->get('status');
	echo " - ";
	echo $connection->get('host');
	echo " [";
	echo $connection->get('user');
	echo ", '";
	echo $connection->get('pass');
	echo "']\nfiles:\n";
	print_r($connection->get('cur_files'));
}

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
