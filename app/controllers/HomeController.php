<?php

namespace app\app\controllers;

use app\app\models\User;
use app\core\Router;
use app\core\Session;

class HomeController
{
    public function index(Router $router)
    {
        if (!Session::isSession('username')) {
            header('Location: /login');
            exit();
        }

        $user = new User();
        $users = $user->all();
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
        $user = new User();
        $users = $user->all();
        return $router->view('users/users', ['users' => $users]);
    }


    public function update(Router $router)
    {
        $id = $router->request->id;
        var_dump($id);
        return $router->view('users/update');
    }


    public function delete(Router $router)
    {
        $id = $router->request->id;
        $user = new User();
        $user->destroy($id);
        header('Location: /users');
    }
}
