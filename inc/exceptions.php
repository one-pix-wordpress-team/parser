<?php
/**
 * Файл исключений
 * 
 * @package moto-parser
 */

// исключения файловой системы
class fileException extends Exception {
    private $FileName;
    public function __construct($message = '', $FileName = '')
    {
        $this->FileName = $FileName;
        parent::__construct($message);
    }

    public function getFileName()
    {
        return $this->FileName;
    }
}

// исключения API
class apiException extends Exception {
    private $url;
    private $request_data;

    public function __construct($message = '', $url = '', $request_data='')
    {
        $this->url = $url;
        $this->request_data = $request_data;
        parent::__construct($message);
    }

    public function getUrl()
    {
        return $this->url;
    }
    
    public function getRequestData()
    {
        return $this->request_data;
    }
}
