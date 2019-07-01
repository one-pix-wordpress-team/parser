<?php
/**
 * Получение данных из файлов для добавления информации к БД
 * 
 * @package moto-parser
 * @version 1.0
 */
class csv_data_parser
{
    protected $files = [];
    protected $main_data = [];

	function __construct(array $files) {
        $this->files = $files;
    }

    function run() {
	    foreach ($this->files as $file) {
	        $file_content = $this->get_file_content($file);
            $file_data = $this->get_data($file_content);
            if (empty($this->main_data)) {
                $this->main_data = $file_data;
            } else {
                $this->main_data = $this->merge($this->main_data, $file_data);
            }
        }

	    return $this->main_data;
    }

	function get_file_content($file)
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

            $row_data = [];
			foreach ($row as $col_no => $value) {
				if ($signature[$col_no] !== false) {
					$row_data[$col_no] = $value;
				}
			}
			if (!empty($row_data))
			    $data[] = $row_data;
//			if ($j++ > 3) {
//				break;
//			}
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

		foreach ($new_data as $new_row) { // перебираем новые данные

            $line = array_pad([], count($signature), ''); // пустая строка, которая будет заполняться данными
		    foreach ($new_row as $new_no_col => $new_col) { // перебираем поля новой строки
		        $type = $new_signature[$new_no_col]; // тип поля
		        if (($no_col = array_search($type, $signature)) === false) { // имеется ли колонка этого типа в общем массиве
                    $no_col = array_push($signature, $type)-1; // добавим, если нет
                }
                $line[$no_col] = $new_col;
            }
            $data[] = $line;

        }

		$count = count($signature);
        $data = array_map(function ($a) use ($count) {
            return array_pad($a, $count, '');
        }, $data); // дополнить строки данных пустыми полями, чтобы длинна была одинаковой

        return [$signature, $data];
	}

	function put_csv($file) {
	    if (empty($this->main_data))
	        return false;

        $head = [];
        foreach ($this->main_data[0] as $no) {
            $head[] = field::AVAILABLE_FIELDS[$no]['name'];
        }

        $content = $this->main_data[1];
        array_unshift($content, $head);

        $csv = new CSV($file);
        $csv->setCSV($content, null, ',');

        return true;
    }
}
