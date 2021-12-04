<?php

namespace App\Core;

class Router
{
    public Request $request;
    protected array $routes = [];

    public function __construct($req)
    {
        $this->request = $req;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;   
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $callback = $this->routes[$method][$path];
        if ($callback === false) {
            echo 'Not found';
            exit;
        }

        echo call_user_func($callback);
    }
}
