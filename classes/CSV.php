<?php
/**
 * Класс для работы с csv-файлами
 * 
 * @package moto-parser
 * @version 1.0
 */
class CSV {
    private $_csv_file = null;

    /**
     * @param string $csv_file  - путь до csv-файла
     */
    public function __construct($csv_file) {
        $this->_csv_file = $csv_file; //Записываем путь к файлу в переменную
        /*
        if (file_exists($csv_file)) { //Если файл существует
            $this->_csv_file = $csv_file; //Записываем путь к файлу в переменную
        } else { //Если файл не найден то вызываем исключение
            throw new Exception("Файл $csv_file не найден");
        }
        */
    }

    /**
     * Запись файла
     * 
     * @param array $csv массив строк будущего файла
     * @param str $mode модификатор доступа к файлу
     */
    public function setCSV(Array $csv, $mode=null) {
        //Открываем csv для до-записи,
        //если указать w, то информация которая была в csv будет затёрта
        if (!file_exists($this->_csv_file)) { //Если файл не существует создаем
            $mode = $mode ?? 'w';
            $handle = fopen($this->_csv_file, $mode);
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
        } elseif(is_writable($this->_csv_file)) {
            $mode = $mode ?? 'a';
            $handle = fopen($this->_csv_file, $mode);
        } else {
            throw new File_Exception("Файл не доступен для записи", $this->_csv_file);
        }

        foreach ($csv as $line) {
            fputcsv($handle, $line, ";");
        }
        fclose($handle);
    }

    /**
     * Чтение из csv-файла. Возвращает массив с данными из csv
     * 
     * @return array массив строк прочитанного файла
     */
    public function getCSV() {
        $handle = fopen($this->_csv_file, "r"); //Открываем csv для чтения

        $array_line_full = [];
        //Проходим весь csv-файл, и читаем построчно
        while (($line = fgetcsv($handle, 0, ";")) !== FALSE) {
            $array_line_full[] = $line; //Записываем строчки в массив
        }
        fclose($handle);
        return $array_line_full; //Возвращаем прочтенные данные
    }
}
