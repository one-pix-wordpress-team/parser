<?php
/**
 * Файл исключений
 * 
 * @package moto-parser
 */

// исключения файловой системы
class fileException extends Exception {
    private $FileName;
    public function __construct($message = "", $FileName = '')
    {
        $this->FileName = $FileName;
        parent::__construct($message);
    }

    public function getFileName()
    {
        return $this->FileName;
    }
}