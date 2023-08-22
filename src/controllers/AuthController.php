<?php

namespace App\controllers;

use Dulannadeeja\Mvc\Application;
use Dulannadeeja\Mvc\Controller;
use Dulannadeeja\Mvc\middleware\AuthMiddleware;
use Dulannadeeja\Mvc\Request;
use App\models\LoginUser;
use App\models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }

    public function login(Request $request): string
    {
        $this->setLayout('auth');

        // create new instance of LoginUser
        $loginModel = new LoginUser();

        if ($request->isPost()) {
            // load data to the model
            $loginModel->loadData($request->getBody());
            // validate data
            $validationResult= $loginModel->validate();
            if(empty($validationResult) && $loginModel->login()){
                // redirect to home page
                Application::$app->response->redirect('/');
                exit;
            }
        }
        $params = [
            'loginModel' => $loginModel
        ];
        return $this->render('login', $params);
    }

    public function register(Request $request): array|string
    {
        $this->setLayout('auth');

        // create new instance of RegisterModel
        $registerModel = new User();

        if ($request->isPost()) {
            // load data to the model
            $registerModel->loadData($request->getBody());
            // validate data
            $validationResult= $registerModel->validate();
            if(empty($validationResult) && $registerModel->register()){
                // redirect to home page
                Application::$app->session->setFlash('success', 'Thanks for registering');
                Application::$app->response->redirect('/');
                exit;
            }

        }
        // render view with errors
        $params = [
            'registerModel' => $registerModel
        ];
        return $this->render('register', $params);

    }

    public function logout(Request $request): void
    {
        Application::$app->logout();
        Application::$app->response->redirect('/');
        exit;
    }

    public function profile(Request $request): string
    {
        $this->setLayout('main');
        return $this->render('profile');
    }

}