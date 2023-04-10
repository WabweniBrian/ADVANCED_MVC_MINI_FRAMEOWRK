<?php

namespace app\core;

class Response
{

    public static function json($data, $status = 200)
    {
        self::status($status);
        header('Content-Type: application/json');
        return json_encode($data);
    }

    public static function send($data, $status = 200)
    {
        self::status($status);
        print_r($data);
    }

    public static function status($status)
    {
        http_response_code($status);
    }
}