<?php

namespace app\core;

use app\core\Database;


class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];
    public Database $db;
    public Request $request;
    public Response $response;
    public function __construct(Request $request, Response $response)
    {
        $this->db = new Database();
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->getRoutes[$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->postRoutes[$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        if ($method == 'GET') {
            $callback = $this->getRoutes[$path] ?? null;
        } else {
            $callback = $this->postRoutes[$path] ?? null;
        }

        if ($callback !== null) {
            call_user_func($callback, $this);
        } else {
            http_response_code(404);
            return $this->view('404', ['title' => '404 Not Found']);
        }
    }

    public function view($view, $params = [])
    {

        foreach ($params as $key => $val) {
            $$key = $val;
        }

        ob_start();
        include_once __DIR__ . "./../views/$view.php";
        $content = ob_get_clean();
        include_once __DIR__ . "./../views/layout/main.php";
    }
}
