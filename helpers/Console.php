<?php

namespace app\helpers;

class Console
{
    public static function dd($arr)
    {
        echo '<pre>', print_r($arr), '</pre>';
    }
}
