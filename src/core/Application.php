<?php

namespace Dulannadeeja\Mvc;


use App\models\User;

class Application
{

    public Router $router;
    public View $view;
    public Request $request;
    public Response $response;
    public static string $ROOT_DIR;
    public ?Controller $controller= null;

    public static Application $app;
    public Database $db;
    public Session $session;
    public ?User $user;

    public string $userClass;

    public function __construct(string $rootPath, array $config)
    {
        // initialize database
        $this->db = new Database($config['db']);

        // initialize request
        $this->request = new Request();

        // initialize response
        $this->response = new Response();

        // initialize router
        $this->router = new Router($this->request, $this->response);

        // initialize view
        $this->view = new View();

        // initialize session
        $this->session = new Session();

        // set app
        self::$app = $this;

        // set root dir
        self::$ROOT_DIR = $rootPath;

        // set user class
        $this->userClass= $config['userClass'];

        // set user
        $primaryValue = $this->session->get('user');
        if($primaryValue){
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }else{
            $this->user = null;
        }

    }

    public static function isGuest(): bool
    {
        return !self::$app->user;
    }

    public function run(): void
    {
        try {
            // resolve paths and methods
            echo $this->router->resolve();
        }catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView("_error", [
                'exception' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode()
                ]
            ]);
        }
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(User $user): void
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        var_dump($primaryValue);
        $this->session->set('user', $primaryValue);
        $this->session->setFlash('success', 'You are logged in');
    }

    public function logout(): void
    {
        $this->user = null;
        $this->session->remove('user');
    }


}