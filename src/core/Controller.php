<?php

namespace Dulannadeeja\Mvc;

use Dulannadeeja\Mvc\middleware\BaseMiddleware;

class Controller
{
    public string $layout = 'main';

    public string $action = '';

    /**
     * @var BaseMiddleware[]
     */
    public array $middlewares = [];

    //render view
    public function render(string $view, array $params = []): string
    {
        return Application::$app->view->renderView($view, $params);
    }

    //set layout
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    //Register middleware
    public function registerMiddleware(BaseMiddleware $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

}