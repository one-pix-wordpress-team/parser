<?php
/**
 * основной файл логики
 * 
 * @package moto-parser
 */

die('nope');

$test_data = [
    'h' => ["UPC","Dealer","Retail price","MAP Price","Brand"],
    'd' => [
        ["01-201",28.50,47.99,47.99,"SHOEI"],
        ["01-202",28.50,47.99,47.99,"DELL"],
        ["01-201",29.50,'',47.99,"SONY"]
    ]
];

$record = FileRecord::init($test_data['h']);
foreach ($test_data['d'] as $row_data) {
    $record->setup($row_data);
    $record->update();
}

echo print_r($record);

exit;
$tc = new tuckerClient;
$response = $tc->price(['160942']);
var_dump($response);

exit;

//echo 'Test done';
$request = 'https://apitest.tucker.com/bin/trws?apikey=8Q9F6FKH9HXAEYHN3KXUG8YXHMP9&cust=1208171&output=JSON&type=INV&item=581010&item=581012&item=581013&zip=12345';
$r2 = 'https://apitest.tucker.com/bin/trws?apikey=8Q9F6FKH9HXAEYHN3KXUG8YXHMP9&cust=1208171&output=JSON&type=INV&item=160942';
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $r2,
    CURLOPT_RETURNTRANSFER => true,
//    CURLOPT_POST => true,
//    CURLOPT_POSTFIELDS => $request,
//    CURLOPT_HTTPHEADER => [
//        'Content-Type: text/xml',
//        'Connection: close',
//    ]
));
$response = curl_exec($curl);
curl_close($curl);

var_dump($response);

exit;

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
