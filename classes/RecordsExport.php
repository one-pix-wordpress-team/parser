<?php

/**
 * Class RecordsExport
 *
 * @package moto-parser
 * @version 1.0
 */
class RecordsExport
{
    protected $available_fields = [];
    protected $signature = [];
    protected $headers = [];
    protected $body = [];

    function __construct()
    {
        $this->available_fields = RecordField::AVAILABLE_FIELDS;
        foreach ($this->available_fields as $f) {
            $this->headers[] = $f['name'];
            $this->signature[] = str_replace(' ', '_', strtolower($f['name']));
        }
    }

    /**
     * Получить массив IDs записей
     *
     * @param int $posts_per_page
     * @param int $offset
     * @return array
     */
    function getRecordsIDs ($posts_per_page = -1, $offset = 0)
    {
        $q = new WP_Query;
        $args = [
            'fields' => 'ids',
            'post_type' => 'bd_record',
            'posts_per_page' => $posts_per_page,
            'offset' => $offset,
        ];
        return $q->query($args);
    }

    /**
     * Получить сформированный массив значений записи для строки файла
     *
     * @param $post_id
     * @return array|false
     */
    function getRecordRow ($post_id)
    {
        $meta_values = get_post_meta($post_id, '', true);
        if (empty($meta_values)) return false;

        $line = array_pad([], count($this->signature), '');
        foreach ($meta_values as $meta_key => $meta_val) {
            $k = array_search($meta_key, $this->signature); // ищем нужный столбец
            if ($k !== false) {
                $line[$k] = $meta_val;
            } else { // если не нейден
                $meta_key_exp = explode(':', $meta_key);
                if (count($meta_key_exp) > 1) { // проверяем является ли название составным
                    $k = array_search($meta_key_exp[0], $this->signature); //
                    if ($k !== false) {
                        array_splice($this->signature, $k + 1, 0, $meta_key);
                        array_splice($this->headers, $k + 1, 0, $this->headers[$k] . ' ' . $meta_key_exp[1]);
                        array_splice($line, $k + 1, 0, $meta_val);
                    }
                }
            }
        }

        return $line;
    }

    function run () {
        $IDs = $this->getRecordsIDs();
        foreach ($IDs as $post_id) {
            $this->body[] = $this->getRecordRow($post_id);
        }

        // очистка массивов от столбцов-маячков для зависимых от диллера значений
        $k_to_del = [];
        foreach ($this->headers as $k => $name) {
            foreach ($this->available_fields as $f) {
                if ($f['name'] === $name && $f['type'] == '4') {
                    $k_to_del[] = $k;
                    break;
                }
            }
        }
        $k_to_del = array_reverse($k_to_del); // от большего к меньшему
        // Т.к. ключи числовые при удалении элементов будут перзаписываться и так мы ничего не испортим
        foreach ($k_to_del as $k) {
            unset($this->headers[$k]);
            foreach ($this->body as &$row) {
                unset($row[$k]);
            }
        }
        unset($row); // удаляем ссылку
    }

    function put_csv($file)
    {
        if (empty($this->body))
            return false;

        array_unshift($this->body, $this->headers);

        $csv = new CSV($file);
        $csv->setCSV($this->body, 'w', ',');

        return true;
    }
}