<?php

namespace app\core;

class Response
{

    public static function json($data, $status = '')
    {
        http_response_code($status);
        header('Content-Type: application/json');
        return json_encode($data);
    }

    public static function send($data)
    {
        echo $data;
    }

    public static function status($status)
    {
        http_response_code($status);
    }
}
