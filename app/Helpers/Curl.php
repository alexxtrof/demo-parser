<?php

namespace App\Helpers;

class Curl
{
    /**
     * Получение содержимого страницы
     *
     * @param string $url
     * @return bool|string
     */
    public static function getHtmlPage(string $url)
    {
        $ch = curl_init();

        // Обязательно задаем useragent, иначе будет ошибка 404
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.82 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
