<?php

namespace Dulannadeeja\Mvc;

class View
{
    public string $title = '';
    public function renderView(string $callback,array $params=[]): string
    {

        //get view
        $viewContent = $this->renderOnlyView($callback,$params);
        //get layout
        $layoutContent = $this->layoutContent();
        //replace content
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    private function layoutContent(): string
    {
        //get layout
        if(Application::$app->controller === null){
            $layout = 'main';
        }else{
            $layout = Application::$app->controller->layout;
        }

        //start output buffer
        ob_start();
        //include layout
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        //return output buffer
        return ob_get_clean();
    }

    public function renderOnlyView(string $callback, array $params=[]): false|string
    {
        //get params
        if ($params) {
            foreach ($params as $key => $value) {
                $$key = $value;
            }
        }
        //start output buffer
        ob_start();
        //include view
        include_once Application::$ROOT_DIR . "/views/$callback.php";
        //return output buffer
        return ob_get_clean();
    }

}