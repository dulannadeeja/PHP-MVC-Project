<?php

namespace Dulannadeeja\Mvc;

use Dulannadeeja\Mvc\Exceptions\NotFoundException;

class Router
{
    private array $routes = [];
    private Request $request;
    private Response $response;


    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $path, callable|string|array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path,callable|string|array $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    //resolve paths and methods

    /**
     * @throws NotFoundException
     */
    public function resolve()
    {
        //get path and method
        $path = $this->request->getPath();
        $method = $this->request->method();

        //get callback
        $callback = $this->routes[$method][$path] ?? false;

        //if callback is false, return not found
        if ($callback === false) {
            // set status code
            $this->response->setStatusCode(404);
            // throw not found exception
            throw new NotFoundException();
        }

        //if callback is string, render view
        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        //if callback is array, call controller
        if (is_array($callback)) {
            //instantiate controller
            Application::$app->setController(new $callback[0]());
            //set action
            Application::$app->controller->action = $callback[1];
            $callback[0] = Application::$app->getController();
            //call controller
            foreach ($callback[0]->middlewares as $middleware) {
                $middleware->execute();
            }
        }

        return $callback($this->request);
    }

}