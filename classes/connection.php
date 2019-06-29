<?php
/**
 * Работа с ftp подключением
 *
 * @package moto-parser
 * @version 1.0
 */
class connection implements initInterface
{
	// protected $name;
	// protected $desc;

	protected $status = 'unable';

	protected $host;
	protected $user;
	protected $pass;

	protected $cur_files;
	protected $local_dir = '';

	protected $conn_id;

	/**
	 * временно публичный
	 * 
	 * @param array $data
	 */
	function __construct($data)
	{
		$download_dir = dataCore::instance()->get_option('download_dir');

		$this->host = !empty($data['host']) ? $data['host'] : '';
		$this->user = $data['user'] ?? '';
		$this->pass = $data['pass'] ?? '';

		// $this->name = $data['name'] ?? $data['host'];
		// $this->desc = $data['desc'] ?? '';

		$this->cur_files = !empty($data['cur_files']) ? array_flip($data['cur_files']) : [];
		$this->local_dir = $download_dir . DIRECTORY_SEPARATOR . $this->host . DIRECTORY_SEPARATOR;
	}

	/**
	 * Разрывает активное соединение при завершении работы
	 */
	function __destruct()
	{
		if (!empty($this->conn_id)) {
			ftp_close($this->conn_id);
		}
	}

	/**
	 * Инициализация подключения
	 * 
	 * @param array $data
	 * @return object connection
	 */
	static function init($data)
	{
		$c = new connection($data);
		$c->connect();
		return $c;
	}

	/**
	 * Возвращает данные для сохранения
	 * 
	 * @return array
	 */
	function data_to_save() {
		return $data = [
			'host' => $this->host,
			'user' => $this->user,
			'pass' => $this->pass,
			'cur_files' => array_keys($this->cur_files),
		];
	}

	/**
	 * Подключение
	 * 
	 * @return bool
	 */
	function connect()
	{
		if ( empty($this->host) ) {
			$this->status = 'incorrect data';
			return false;
		}
		
		// установка соединения
		$conn_id = ftp_connect($this->host);
		$login_result = false;
		if ($conn_id) {
			// вход с именем пользователя и паролем
			$login_result = ftp_login($conn_id, $this->user, $this->pass);
		}

		// проверка соединения
		if ((!$conn_id) || (!$login_result)) {
		    $this->status = 'connect failed';
			if ($conn_id) // закрытие соединения
				ftp_close($conn_id);
		} else {
			$this->conn_id = $conn_id;
		    $this->status = 'successfully connected';
			$this->get_files(true);	// проверка нужных файлов
		}

		return $login_result;
	}

	/**
	 * Получить список доступных файлов на сервере
	 * 
	 * @param str $server_dir
	 * @return false|array
	 */
	function get_files_list($server_dir)
	{
		if(empty($this->conn_id))
			return false;
		return ftp_nlist($this->conn_id, $server_dir);
	}

	/**
	 * получить нужные файлы с сервера
	 *
	 * @param bool $test проверить на наличие, вместо скачивания
	 * @return bool|array
	 */
	function get_files($test=false)
	{
		if(empty($this->conn_id))
			return false;

		$downloaded = [];
		foreach ($this->cur_files as $file => &$status) {
			if ($test) {
				$file_in_dir = ftp_nlist($this->conn_id, dirname($file));
				if ( is_array($file_in_dir) && in_array($file, $file_in_dir) ) {
				    $status = 'available';
				} else {
				    $status = '<div class="red">unavailable</div>';
				}
			} else {
				if (!file_exists($this->local_dir)) // создаем директорию, если ее нет
					mkdir($this->local_dir, 0777, true);

				if (ftp_get($this->conn_id, $this->local_dir . basename($file), $file, FTP_BINARY)) {
				    $status = 'downloaded';
//				    echo $this->local_dir . basename($file);
//				    echo "\n";
//				    echo $file;
                    $downloaded[] = $this->local_dir . $file;
				} else {
				    $status = '<div class="red">download failed</div>';
				}
			}
		}

		if ($test) {
            return true;
        } else {
            return $downloaded;
        }
	}

	/**
	 * лайтовый геттер
	 *
	 * @return mixed
	 */
	function get($name) {
		return $this->$name;
	}
}
