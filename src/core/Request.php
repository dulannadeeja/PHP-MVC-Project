<?php

namespace Dulannadeeja\Mvc;

class Request
{
    public function getPath(): string
    {
        //get path
        return explode('?', $_SERVER['REQUEST_URI'])[0] ?? '/';
    }

    public function method()
    {

        return $_SERVER['REQUEST_METHOD'];
    }

    public function isGet(): bool
    {

        return $this->method() === 'GET';
    }

    public function isPost(): bool
    {

        return $this->method() === 'POST';
    }

    public function getBody(): array
    {

        $body = [];

        if ($this->method() === 'GET') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->method() === 'POST') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }

}