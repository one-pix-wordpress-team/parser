<?php
/**
 * Работа с ftp подключением
 *
 * @package moto-parser
 * @version 1.4
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

		$this->cur_files = !empty($data['cur_files']) && is_array($data['cur_files']) ? array_flip($data['cur_files']) : [];
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
	 * @param string $server_dir нужная директория
	 * @param int $max_depth глубина прохода, при 0 только указанная директория
	 * @param bool $show_empty показывать ли пустые папки
	 * @return false|array
	 */
	function get_files_list(string $server_dir = '.', $max_depth = 1, $show_empty = false)
	{
		if(empty($this->conn_id))
			return false;

		static $depth = 0;
		if ($depth > $max_depth) return [];
		$depth++;

		$list = ftp_nlist($this->conn_id, $server_dir);

		$dirs = [];
		foreach ($list as $file) {
			if (false === strpos($file, '.')) {
				$l = $this->get_files_list($file, $max_depth);
				if (!empty($l) || $show_empty) {
					$dirs[$file] = $l;
				}
			} else {
				if (preg_match('/\.csv$/', $file)) {
					$dirs[] = $file;
				}
			}
		}

		$depth--;
		return $dirs;
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
				    $status = 'unavailable';
				}
			} else {
				if (!file_exists($this->local_dir)) // создаем директорию, если ее нет
					mkdir($this->local_dir, 0777, true);

				if (ftp_get($this->conn_id, $this->local_dir . basename($file), $file, FTP_BINARY)) {
				    $status = 'downloaded';
                    $downloaded[] = $this->local_dir . basename($file);
				} else {
				    $status = 'download failed';
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
	 * устанавливает актуальные файлы для подключения
	 *
	 * @param array $files
	 * @param bool $add добавить файлы вместо затирания
	 * @return bool
	 */
	function set_cur_files(array $files, $add=false)
	{
		if ($add) {
			$this->cur_files = array_merge($this->cur_files, array_flip($files));
		} else {
			$this->cur_files = array_flip($files);
		}

		return true;
	}

	/**
	 * лайтовый геттер
	 *
     * @param string $name
     * @return mixed
	 */
	function get($name) {
		return $this->$name;
	}
}
