<?php

namespace App\Services;

use GuzzleHttp\Client;

class TelegramService
{
    private static $_url = 'https://api.telegram.org/bot';
    private static $_token = '5331786755:AAGyyeSns6rrcsMPRWr8Za_IFOmxnnwLVLI';
    private static $_chat_id = '-667596845';

    public static function sendMessage($text)
    {
        $uri = self::$_url . self::$_token . '/sendMessage?parse_mode=html';
        $params = [
            'chat_id' => self::$_chat_id,
            'text' => $text,
        ];
        $option['verify'] = false;
        $option['form_params'] = $params;
        $option['http_errors'] = false;
        $client = new Client();
        $response = $client->request("POST", $uri, $option);
        return json_decode($response->getBody(), true);
    }

    public static function sendError($exception)
    {
        $html = '<b>[Error] : </b><code>' . $exception->getMessage() . '</code>' . PHP_EOL;
        $html .= '<b>[File] : </b><code>' . $exception->getFile() . '</code>' . PHP_EOL;
        $html .= '<b>[Line] : </b><code>' . $exception->getLine() . '</code>' . PHP_EOL;
        $html .= '<b>[Data Request] : </b><code>' . json_encode(request()->all()) . '</code>' . PHP_EOL;
        $html .= '<b>[URL] : </b><a href="' . url()->full() . '">' . url()->full() . '</a>' . PHP_EOL;
        $html .= '<b>[Timestamp] : </b><code>' . now() . '</code>' . PHP_EOL;
        self::sendMessage($html);
    }
}
