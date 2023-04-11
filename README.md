# ADVANCED MVC MINI FRAMEWORK

In this framework, we implement alot of features an mvc framework has such as

- Models, Views & Contollers
- Custom routing
- PSR-4 Autoloading
- Custom 404
- User authentication and validation with multiple validation rules
- Custom Validation class with generic validation rules
- Validator class with different validation rules and methods
- Generic model class that can be extended by any class
- Custom Response, Request, Session and Cookie classes
- Eloquent model with methods such as save, all, find,destroy etc.
- Searching for any fields in the table

## Code Snippets;

### Entry File

```php
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

```

### User Registration

```php
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
            $user->registerUser($errors);
        }
        return $router->view('auth/register', ['title' => 'Register', 'user' => $userData, 'errors' => $errors]);
    }
```

### User Login

```php
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
            $errors['credential_err'] = $user->loginUser($errors);
        }

        return $router->view('auth/login', ['title' => 'Login', 'user' => $userData, 'errors' => $errors]);
    }
```

### User Logout

```php
  public function logout()
    {
        $user = new User();
        $user->logoutUser();
    }
```

### User Model class

```php
namespace app\app\models;

use app\core\Model;

class User extends Model
{

    protected $table = 'users';
    protected $fillable = ['username', 'email', 'password'];
}
```

### Home controller

```php
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

    public function userData(Router $router)
    {
        $user = new User();
        $users = $user->all();
        $jsonData =  $router->response->json($users, 200);

        $router->response->send($jsonData);

        $router->view('users/userData');
        return $jsonData;
    }


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
                $user->save();
                header('Location: /users');
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
```

- Those are a few of the code snippets
