<?php

namespace app\core;

class Request
{
    protected $data;

    public function __construct()
    {
        $this->data = $this->sanitize(array_merge($_GET, $_POST));
    }

    private function sanitize($data)
    {
        if (is_array($data)) {
            $sanitizedData = array();
            foreach ($data as $key => $value) {
                $sanitizedData[$key] = $this->sanitize($value);
            }
        } else {
            if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
                $data = filter_var($data, FILTER_SANITIZE_EMAIL);
            } else {
                $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
            }
            $sanitizedData = $data;
        }
        return $sanitizedData;
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    public function __set($key, $value)
    {
        if (in_array($key, $this->data)) {
            $this->attributes[$key] = $value;
        }
    }

    public function value($name)
    {
        return $this->data[$name] ?? null;
    }

    public function all()
    {
        return $this->data;
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }


    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        if (strpos($path, '?') !== false) {
            $path = substr($path, 0, strpos($path, '?'));
        }
        return $path;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
