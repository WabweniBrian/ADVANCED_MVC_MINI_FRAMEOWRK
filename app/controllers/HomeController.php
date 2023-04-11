<?php

namespace app\app\controllers;

use app\app\models\User;
use app\core\Router;
use app\core\Session;
use app\helpers\Console;
use app\helpers\Validator;

class HomeController
{
    public function index(Router $router)
    {
        if (!Session::isSession('username')) {
            header('Location: /login');
            exit();
        }
        return $router->view('users/index');
    }


    public function single(Router $router)
    {
        $id = $router->request->id;
        $user = new User();
        $user = $user->find($id);
        return $router->view('users/singleUser', ['user' => $user]);
    }


    public function users(Router $router)
    {
        $searchTerm = $router->request->search ?? '';
        $user = new User();
        $users = $user->all(['username' => $searchTerm, 'email' => $searchTerm]);
        return $router->view('users/users', ['users' => $users, 'search' => $searchTerm]);
    }

    // public function userData(Router $router)
    // {
    //     $user = new User();
    //     $users = $user->all();
    //     $jsonData =  $router->response->json($users, 200);

    //     // $router->response->send($jsonData);

    //     $router->view('users/userData');
    //     return $jsonData;
    // }


    public function update(Router $router)
    {
        $errors = ['username' => '', 'email' => ''];
        $user = ['username' => '', 'email' => ''];
        $id = $router->request->id;
        $user = new User();
        $user = $user->find($id);

        if ($router->request->isPost()) {

            $userData = $router->request->all();

            $user = new User($userData);

            $errors = Validator::validate(
                $userData,
                [
                    'username' => ['required', 'min:4', 'max:20', 'unique:username'],
                    'email' => ['required', 'email', 'unique:email'],
                ]
            );

            Console::dd($errors);

            if (empty(array_filter($errors))) {
                // $user->save();
                // header('Location: /users');
                exit();
            }
        }

        return $router->view('users/update', ['user' => $user, 'errors' => $errors]);
    }


    public function delete(Router $router)
    {
        $id = $router->request->id;
        $user = new User();
        $user->destroy($id);
        header('Location: /users');
    }
}
