<?php

namespace app\core;

class Cookies
{
    public static function setCookies(array $user): void
    {
        // Set remember me cookie if checked
        if (isset($user['remember_me']) && $user['remember_me'] == 'on') {
            // Set a cookie to remember the user
            setcookie('remember_me', 'on', time() + 3600 * 24 * 30);
            setcookie('email', $user['email'], time() + 3600 * 24 * 30);
            $user['remember_me'] = true;
        } else {
            // If the checkbox is not checked, delete the cookie
            setcookie('remember_me', '', time() - 3600);
            setcookie('email', '', time() - 3600);
            $user['remember_me'] = false;
        }
    }
}