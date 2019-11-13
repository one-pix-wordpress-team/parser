<?php
/**
 * Class FileRecord
 *
 * Класс предназначен для работы со строками файла.
 *
 * @package moto-parser
 * @version 1.0
 */
class FileRecord
{
    /**
     * @var array массив объектов полей
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $row_signature = [];

    /**
     * @var mixed id записи в БД
     */
    protected $post_id = null;

    protected function __construct() {}

    /**
     * Производит инициализацию записи по переданным заголовкам
     *
     * @param array $headers
     * @param $source
     * @return FileRecord|false
     */
    static function init(array $headers, $source = '')
    {
        $record = new self;
        foreach ($headers as $name) {
            $field = RecordField::init($name, $source);

            $record->row_signature[] = $field;

            if ($field !== false)
                $record->fields[] = $field;
        }

        if ($record->get_upc())
            return $record;
        else
            return false;
    }

    /**
     * устанавливает значения для полей
     *
     * @param array $data
     */
    function setup(array $data)
    {
        foreach ($this->row_signature as $k => $f) {
            if (empty($f) || !isset($data[$k])) continue;
            $f->setup($data[$k]);
        }
        $this->post_id = $this->search();
    }

    /**
     * Производит соотнесение записи с записью в БД
     *
     * @return string|null
     */
    function search() {
        $upc = $this->get_upc()->value;
        $q = new WP_Query;
        $args = [
            'fields' => 'ids',
            'post_type' => 'bd_record',
            'meta_key' => 'upc',
            'meta_value' => $upc,
        ];
        $r = $q->query($args);
        if (empty($r))
            return null;
        else
            return array_shift($r);
    }

    /**
     * Возвращает объект поля с UPC, если он есть
     */
    function get_upc() {
        static $upc_field = null;
        if (!isset($upc_field)) {
            foreach ($this->fields as $f) {
                if ($f->name === 'UPC')
                    $upc_field = $f;
            }
        }
        return $upc_field;
    }

    /**
     * Обновляет запись
     *
     * @return bool
     */
    function update()
    {
        if (!isset($this->post_id)) {
            $this->post_id = wp_insert_post([
                'post_title'   => $this->get_upc()->value,
                'post_content' => '',
                'post_status'  => 'publish',
                'post_type'    => 'bd_record',
            ]);
        }
        if (empty($this->post_id)) return false;
        foreach ($this->fields as $field) {
            $field->update($this->post_id);
        }
        return true;
    }
}
