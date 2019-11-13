<?php

/**
 * Class Partsnetweb
 *
 * @package moto-parser
 * @version 1.0
 */
class Partsnetweb
{
    protected $options = [
        'download_dir' => '',
        'cookie_file' => __DIR__ .'/puCookie.txt',
    ];

    protected $access;

    protected function __construct($access)
    {
        $this->access = $access;
        $this->options['download_dir'] = dataCore::instance()->get_option('download_dir') . '/partsnetweb';
        $this->options['cookie_file'] = $this->options['download_dir'] . '/puCookie.txt';
    }

    static function init ()
    {
        static $instance = null;
        if (!isset($instance)) {
            $core = dataCore::instance();
            $access = $core->get_data('partsUnlimited');
            if (!empty($access) && is_array($access)) {
                $access = array_shift($access);
                $instance = new self($access);
            } else {
                return false;
            }
        }
        return $instance;
    }

    /**
     * Производит curl запросы
     *
     * @param $url
     * @param string $post
     * @param string $Referer
     * @param bool $header при получении файла нужно отключить
     * @return bool|string
     */
    protected function curlRequest($url, $post = '', $Referer = '', $header=true)
    {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_COOKIEFILE => $this->options['cookie_file'],
            CURLOPT_COOKIEJAR => $this->options['cookie_file'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post,
             CURLOPT_HEADER => $header,
            CURLOPT_REFERER => $Referer,
            CURLOPT_HTTPHEADER => [
                // 'Cookie: SITE=PNW; HOSTNAME=prd-ap03vm.external.com; _ga=GA1.2.275099260.1566971336; _gid=GA1.2.1076490385.1566971336; CDID=LOGOUT; CUID=LOGOUT; _gat=1',
                // 'Content-Type: text/xml',
                // 'Sec-Fetch-Mode: no-cors',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36',
                // 'Connection: keep-alive',
                // 'Cache-Control: max-age=0',
                // 'Upgrade-Insecure-Requests: 1',
                // 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
                // 'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            ]
        ));
        $response = curl_exec($ch);
        // $header = curl_getinfo($ch);
        curl_close($ch);
        // return [$response, $header];
        return $response;
    }

    /**
     * Производит авторизацию
     *
     * @param string $dealer_id
     * @param string $user
     * @param string $pwd
     */
    protected function authorise($dealer_id = '', $user = '', $pwd = '')
    {
        $post = [
            'load_time' => '1.1559999999999988',
            'dealer_id' => $dealer_id,
            'user_id' => $user,
            'password' => $pwd,
            'form_action' => 'LOGIN',
            // 'remember' => '',
            'x' => '105',
            'y' => '24',
        ];
//        $post_str = 'load_time=1.1559999999999988&dealer_id=MT0306&user_id=Motoworld&password=motoworld1&form_action=LOGIN&x=105&y=24';

        $this->curlRequest('https://www.partsnetweb.com/index.jsp');
        $this->curlRequest('https://www.partsnetweb.com/checkLogin.jsp?redirect=', http_build_query($post), 'https://www.partsnetweb.com/index.jsp');
        $this->curlRequest('https://www.partsnetweb.com/checkLogin2.jsp?redirect=', '', 'https://www.partsnetweb.com/checkLogin.jsp?redirect=');
        $this->curlRequest('https://www.partsnetweb.com/main.jsp?redirect=', '', 'https://www.partsnetweb.com/checkLogin2.jsp?redirect=');
    }

    /**
     * Скачивание архива с файлом
     *
     * @param string $file название для скаченного архива
     */
    protected function downloadArchive($file)
    {
        $response = $this->curlRequest('https://www.partsnetweb.com/pnwproxy/dm/4/part-info/pricing/2013', 'dealer_id=MT0306&includeHeaders=1&upcCode=1&brandName=1&countryOfOrigin=1&productCode=1&dragPart=1&weight=1&closeoutCatalogIndicator=1&streetCatalog=1&fatbookCatalog=1&atvCatalog=1&offroadCatalog=1&snowCatalog=1&waterCatalog=1&streetMidYearCatalog=1&fatbookMidYearCatalog=1&helmetApparelCatalog=1&tireCatalog=1&oldbookCatalog=1&oldbookMidYearCatalog=1&rememberSelections=1', 'https://www.partsnetweb.com/main.jsp?Nav=Report&account_subnav=downloads/price_files_new', false);
        if (!file_exists(dirname($file))) // создаем директорию, если ее нет
            mkdir(dirname($file), 0777, true);
        file_put_contents($file, $response);
    }

    /**
     * Получить csv файл
     *
     * @return false|string
     */
    function getCSVFile ()
    {
        $download_only = false;
        $file = $this->options['download_dir'] . '/archive.zip';
        $this->authorise($this->access['dist_id'], $this->access['dist_user'], $this->access['dist_password']);
        $this->downloadArchive($file);

        $zip = new ZipArchive;
        if ($zip->open($file) === TRUE) {
            $scv_file_name = '';
            $count = $zip->numFiles;
            for ($i = 0; $i < $count; $i++) {
                $stat = $zip->statIndex($i);
                if(strpos($stat['name'], '.csv')) {
                    $scv_file_name = $stat['name'];
                    break;
                }
            }
            if ($scv_file_name) {
                $zip->extractTo($this->options['download_dir'] . '/', $scv_file_name);
                $zip->close();
                return $this->options['download_dir'] . '/'. $scv_file_name;
            } else {
                $zip->close();
                if (!$download_only) { // если допускается использовать ранее скачанный файл, то ищем такой
                    $files = scandir($this->options['download_dir']);
                    foreach ($files as $scv_file_name) {
                        if(strpos($scv_file_name, '.csv'))
                            return $this->options['download_dir'] .'/'. $scv_file_name;
                    }
                }
                return '';
            }
        } else {
            return false;
        }
    }
}
