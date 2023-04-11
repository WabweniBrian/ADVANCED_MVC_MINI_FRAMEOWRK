<?php

require_once __DIR__ . './../vendor/autoload.php';

use app\app\controllers\AuthController;
use app\app\controllers\HomeController;
use app\core\Application;

$app = new Application();


// Auth Routes
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/logout', [AuthController::class, 'logout']);

// Home Routes
$app->router->get('/', [HomeController::class, 'index']);
$app->router->get('/users', [HomeController::class, 'users']);
$app->router->get('/users/search', [HomeController::class, 'users']);
$app->router->get('/users/view', [HomeController::class, 'single']);
$app->router->get('/users/update', [HomeController::class, 'update']);
$app->router->post('/users/update', [HomeController::class, 'update']);
$app->router->post('/users/delete', [HomeController::class, 'delete']);
$app->router->get('/users/userData', [HomeController::class, 'userData']);

$app->run();
