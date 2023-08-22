<?php

namespace App\controllers;

use Dulannadeeja\Mvc\Application;
use Dulannadeeja\Mvc\Controller;
use Dulannadeeja\Mvc\Request;
use App\models\Contact;

class SiteController extends Controller
{

    public function home()
    {
        $params = [
            'name' => 'John Doe'
        ];
        return $this->render('home', $params);
    }

    public function contact(Request $request)
    {
        $contactModel = new Contact();

        // check if request is post
        if ($request->isPost()) {
            // load data to the model
            $contactModel->loadData($request->getBody());
            // validate data
            if (!$contactModel->validate() && $contactModel->send()) {
                Application::$app->session->setFlash('success', 'Thanks for contacting us.');
                Application::$app->response->redirect('contact');
                exit;
            }
        }
        // render contact view
        return $this->render('contact', [
            'contactModel' => $contactModel
        ]);
    }


}