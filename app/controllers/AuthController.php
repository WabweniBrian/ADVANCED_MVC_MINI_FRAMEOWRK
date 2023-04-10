<?php

namespace app\app\controllers;

use app\app\models\User;
use app\core\Cookies;
use app\core\Router;
use app\core\Session;
use app\helpers\Validator;


class AuthController
{

    public function register(Router $router)
    {

        $userData = ['username' => '', 'email' => '', 'password' => '', 'password_confirmation' => ''];
        $errors = ['username' => '', 'email' => '', 'password' => '', 'password_confirmation' => '', 'avatar' => ''];

        if ($router->request->isPost()) {

            $userData = $router->request->all();
            $userData['avatar'] = $_FILES['avatar'] ?? null;

            $user = new User($userData);
            $errors = Validator::validate(
                $userData,
                [
                    'username' => ['required', 'min:4', 'max:20', 'unique:username'],
                    'email' => ['required', 'email', 'unique:email'],
                    'password' => ['required', 'min:4', 'max:20'],
                    'password_confirmation' => ['required', 'match:password'],
                    'avatar' => ['file', 'size:1048576'],
                ]
            );

            $user->hashed_password = password_hash($user->password, PASSWORD_DEFAULT);

            if (empty(array_filter($errors))) {
                $user->registerUser();
                Session::setSession(['username' => $user->username, 'email' => $user->email]);
                header('Location: /');
            }
        }
        return $router->view('auth/register', ['title' => 'Register', 'user' => $userData, 'errors' => $errors]);
    }


    public function login(Router $router)
    {
        $errors = ['email' => '', 'password' => ''];
        $userData = ['email' => '', 'password' => '', 'remember_me' => false];

        if (isset($_COOKIE['remember_me']) && $_COOKIE['remember_me'] == 'on') {
            $userData['email'] = $_COOKIE['email'] ?? '';
            $userData['remember_me'] = true;
        } else {
            $userData['email'] = '';
            $userData['remember_me'] = false;
        }

        if ($router->request->isPost()) {
            $userData = $router->request->all();

            $user = new User($userData);

            $errors = Validator::validate(
                $userData,
                [
                    'email' => ['required'],
                    'password' => ['required'],
                ]
            );

            Cookies::setCookies($userData);

            if (empty(array_filter($errors))) {
                $isLoggedIn = $user->loginUser();
                if ($isLoggedIn) {
                    header("Location: /");
                } else {
                    $errors['credential_err'] = 'Invalid email or password.';
                }
            }
        }

        return $router->view('auth/login', ['title' => 'Login', 'user' => $userData, 'errors' => $errors]);
    }


    public function logout()
    {
        $user = new User();
        $user->logoutUser();
    }
}
