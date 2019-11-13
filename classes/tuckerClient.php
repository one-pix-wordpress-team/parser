<?php

/**
 * Класс для работы с API Tucker
 *
 * @package moto-parser
 * @version 1.0
 */
class tuckerClient {
    protected $host = 'apitest.tucker.com';
    protected $apikey = '8Q9F6FKH9HXAEYHN3KXUG8YXHMP9';
    protected $cust = '1208171';
    protected $output = 'JSON';

    /**
     * Производит curl запрос
     *
     * @param $url
     * @param array $post_fields
     * @return bool|string
     */
    function curl_request($url, $post_fields=[])
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_HTTPHEADER => [
//                'Content-Type: text/xml',
//                'Connection: close',
//            ]
        ));
        if (!empty($post_fields)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
        }

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * Подготавливает конечные параметры для curl запроса в зависимости от настроек
     *
     * @param $fields
     * @return mixed
     */
    function request($fields) {
        $fields = array_merge($fields, [
            'apikey' => $this->apikey,
            'cust' => $this->cust,
            'output' => $this->output,
        ]);

        $params = '';
        foreach ($fields as $field => $value) {
            if (is_array($value)) {
                foreach ($value as $val)
                    $params .= $field . '=' . $val . '&';
            } else {
                $params .= $field . '=' . $value . '&';
            }
        }
        $params = trim($params, '&');
        $url = 'https://' . $this->host . '/bin/trws';
        $url .= '?' . $params;
        echo $url;

        $response = $this->curl_request($url);
        $response = json_decode($response, true);
        if ($response == null) {
            throw new apiException('Ошибка API запроса', $url, $fields);
        }
        return $response;
    }

    /**
     * Подготовка inventory запроса
     *
     * @param array $items
     * @param string $zip
     * @return mixed
     */
    function inventory(array $items, $zip='') {
        $fields['type'] = 'INV';
        $fields['item'] = $items;
        if (!empty($zip))
            $fields['zip'] = $zip;

        return $this->request($fields);
    }

    /**
     * Подготовка price запроса
     *
     * @param array $items
     * @return mixed
     */
    function price(array $items) {
        $fields['type'] = 'PRC';
        $fields['item'] = $items;
        return $this->request($fields);
    }
}