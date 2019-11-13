<?php
/**
 * Получение данных из файлов для добавления информации к БД
 *
 * @package moto-parser
 * @version 1.0.1
 */
class bd_data_parser
{
    protected $files = [];
    protected $header_rules = [];
    protected $distributor; // поставщик обрабатываемого файла

    /**
     * bd_data_parser constructor.
     *
     * @param array $files
     */
    function __construct(array $files)
    {
        $this->files = $files;

        $this->header_rules = dataCore::instance()->get_cash('header_rules');
//        Соотносит реальные названия столбцов в файлах с одобренными
//        $header_rules = [
//            'Part #' => 'Part Number',
//            'Dealer' => 'Dealer',
//            'UPC' => 'UPC',
//        ];
    }

    function run()
    {

//        $file = '/var/www/admin/data/www/parser.one-pix.com/wp-content/plugins/moto-parser/download/ftp.wpsstatic.com/WPS_Daily_Combined.csv';

//                $handle = fopen($file, 'r');
//                $line = fgetcsv($handle, 0, ',');
//                fclose($handle);
//            $file_content = $this->get_file_content($file);
//        die(print_r($file_content, true));

        if (empty($this->header_rules))
            return 'header rules is empty';

        foreach ($this->files as $k => $file) {
            if (strpos($file, 'WPS_Daily_Combined')) continue; // заплатка
            /**
             * название поставщика определяется по названию папки в котором лежит файл.
             */
            $this->distributor = basename(dirname($file));

            $file_content = $this->get_file_content($file);
            if (count($file_content) < 2) continue; // пропускаем пустые файлы


            $headers = array_shift($file_content);
            foreach ($headers as &$header) { // замена заголовков в файле на соответствующие им одобренные заголовки
                if (!empty($this->header_rules[$header]))
                    $header = $this->header_rules[$header];
            }
            unset($header); // уничтожили ссылочку


            $record = FileRecord::init($headers, $this->distributor); // инициализация шаблона строки для текущего файла
            foreach ($file_content as $row) {
                $record->setup($row);
                $record->update();
            }
        }

        return true;
    }

    /**
     * Чтение данных из файла
     *
     * @param $file
     * @return array|false
     */
    function get_file_content($file)
    {
        if (!is_writable($file))
            return false;
        $handle = fopen($file, 'r'); //Открываем csv для чтения
        if (!$handle)
            return false;
        $array_line_full = [];
        //Проходим весь csv-файл, и читаем построчно
        $c = 0;
        while ($c++<1002 && ($line = fgetcsv($handle, 0, ',')) !== FALSE && isset($line)) {
            if ($line[0] === null) continue; // пропуск пустой строки
            $array_line_full[] = $line; //Записываем строчки в массив
        }
        fclose($handle);
//        $array_line_full = array_slice($array_line_full, 0, 11);
        return $array_line_full;

        $csv = new CSV($file);
        $content = $csv->getCSV(',');
        $content = array_slice($content, 0, 1001); // ради теста. Не берем бесконечные файлы, а берем только первые 101 строк
        return $content;
    }
}
