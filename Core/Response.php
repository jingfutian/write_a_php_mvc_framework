<?php

namespace App\Core;

class Response
{
    public function setStatusCode($code)
    {
        http_response_code($code);
    }

    public function redirect($path)
    {
        header('Location: ' . $path);
    }
}