<?php
/**
 * Получение данных из файла для добавления информации к БД
 * 
 * @package moto-parser
 * @version 1.0
 */
class csv_data_parser
{
	function __construct() {}

	function file_get_content($file)
	{
		$csv = new CSV($file);
		$content = $csv->getCSV(',');
		return $content;
	}

	function get_data(array $file_content)
	{
		// Если вдруг у нас нет контента
		if (empty($file_content) || count($file_content) < 2)
			return false;

		// проходим по полям заголовка, чтобы понять какие столбцы содержат нужную нам информацию
		$signature = array_map(function($n){
			return field::is_field($n);
		}, $file_content[0]);

		// перебираем файл доставая значения только из нужных полей
		$data = [];
		$j = 0;
		foreach ($file_content as $row_no => $row) {
			if ($row_no === 0) continue;

			// $line_fields = array_map(function($val, $no_val) use ($signature) {
			// 	if ($signature[$no_val] === false)
			// 		return false;

			// 	return field::init([
			// 		'field' => $signature[$no_val],
			// 		'value' => $val
			// 	]);
			// }, $row, array_keys($row));

			foreach ($row as $col_no => $value) {
				if ($signature[$col_no] !== false) {
					$row_data[$col_no] = $value;
				}
			}
			$data[] = $row_data;
			if ($j++ > 10) {
				break;
			}
		}
		$signature = array_filter($signature, function($s) { return $s !== false; }); // оставляем только допустимые поля

		return [$signature, $data];
	}

	function merge($d, $new_d)
	{
		$signature = $d[0];
		$data = $d[1];
		$new_signature = $new_d[0];
		$new_data = $new_d[1];


	}
}
