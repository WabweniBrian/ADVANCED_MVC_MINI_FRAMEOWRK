<?php

namespace app\core;

class Session
{
    public static function setSession($fields): void
    {
        session_start();
        foreach ($fields as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    public static function isSession($value): bool
    {
        session_start();
        return isset($_SESSION[$value]);
    }
}
