<?php
/**
 * Получение данных из файлов для добавления информации к БД
 * 
 * @package moto-parser
 * @version 1.1
 */
class csv_data_parser
{
    protected $files = [];
    protected $main_data = [];
    protected $available_fields = [];

    protected $cur_file; // текущий обрабатываемый файл
    protected $distributor; // поставщик обрабатываемого файла

	function __construct(array $files)
    {
        $this->files = $files;
        $this->available_fields = field::AVAILABLE_FIELDS;
    }

    function run()
    {
	    foreach ($this->files as $file) {
            $this->cur_file = $file;
            /**
             * название поставщика определяется по названию папки в котором лежит файл.
             */
            $this->distributor = basename(dirname($file));
	        $file_content = $this->get_file_content($file);
	        if (count($file_content) < 2) continue; // пропускаем пустые файлы
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
        $content = array_slice($content, 0, 1001); // ради теста. Не берем бесконечные файлы, а берем только первые 1001 строк
		return $content;
	}

	function is_field($s_name) {
        if (($field = field::is_field($s_name, $this->available_fields)) !== false) {
            if ($this->available_fields[$field]['type'] == 3) { // если поле зависит от поставщика, создаем под него отдельный временный тип
                $new_name = $this->distributor . '_' . $this->available_fields[$field]['name'];
                if (($new_field = field::is_field($new_name, $this->available_fields)) !== false) { // не создан ли уже отдельный тип
                    $field = $new_field;
                } else {
                    $t = [
                        'type' => 0,
                        'name' => $new_name,
                    ];
                    $field = count($this->available_fields);
                    $this->available_fields[$field] = $t;
                }
            }
        } else {
            file_put_contents(ROOT_DIR . '/log_file', "Неизвестный тип поля '$s_name' в файле " . $this->distributor . '/' . basename($this->cur_file) . "\n", FILE_APPEND);
        }
        return $field;
    }

	function get_data(array $file_content)
	{
		// Если вдруг у нас нет контента
		if (empty($file_content) || count($file_content) < 2)
			return false;

		// проходим по полям заголовка, чтобы понять какие столбцы содержат нужную нам информацию
		$signature = array_map(function($n){
			return $this->is_field($n);
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

		// выделить из новой сигнатуры ключевые поля
        $key_fields = array_filter($new_signature, function($f){
            return $this->available_fields[$f]['type'] == 2;
        });

		foreach ($new_data as $new_row) { // перебираем новые данные

            $found_lines = [];
            if (!empty($key_fields)) {
                // получим массив ключей, которые имеются в сигнатуре общего массива
                foreach ($signature as $in_old => $t) {
                    if ($in_new = array_search($t, $key_fields)) $keys[$in_new] = $in_old;
                }

                if (!empty($keys)) { // произвести поиск строки в массиве данных по ключевым полям, если они есть
                    $found_lines = array_filter($data, function ($l) use ($keys, $new_row) {
                        foreach ($keys as $in_new => $in_old) { // проверка каждого ключевого поля
                            if (empty($l[$in_old]) || empty($new_row[$in_new])) // если поля пустые, то и сравнивать нечего
                                return false;
                            if ($l[$in_old] != $new_row[$in_new]) // если не совпадает - пропуск
                                return false;
                        }
                        return true;
                    });
                }
            }

            if (empty($found_lines)) {
                $line = array_pad([], count($signature), ''); // пустая строка, которая будет заполняться данными
                $line_no = null;
            } else { // использовать для заполнения найденную строку (если нашли)
                $line = reset($found_lines);
                $line_no = key($found_lines);
            }

		    foreach ($new_row as $new_no_col => $new_col) { // перебираем поля новой строки
		        $type = $new_signature[$new_no_col]; // тип поля
		        if (($no_col = array_search($type, $signature)) === false) { // имеется ли колонка этого типа в общем массиве
                    $no_col = array_push($signature, $type)-1; // добавим, если нет
                }
                $line[$no_col] = $new_col;
            }
		    if (isset($line_no))
		        $data[$line_no] = $line;
		    else
                $data[] = $line;
        }

		$count = count($signature);
        $data = array_map(function ($a) use ($count) {
            return array_pad($a, $count, '');
        }, $data); // дополнить строки данных пустыми полями, чтобы длинна была одинаковой

        return [$signature, $data];
	}

	function put_csv($file)
    {
	    if (empty($this->main_data))
	        return false;

        $head = [];
        foreach ($this->main_data[0] as $no) {
            $head[] = $this->available_fields[$no]['name'];
        }

        $content = $this->main_data[1];
        array_unshift($content, $head);

        $csv = new CSV($file);
        $csv->setCSV($content, 'w', ',');

        return true;
    }
}
