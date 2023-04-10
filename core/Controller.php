<?php

namespace app\core;

class Controller
{

    protected Request $request;
    protected Router $router;

    public function __construct(Request $request, Router $router)
    {
        $this->request = $request;
        $this->router = $router;
    }
}