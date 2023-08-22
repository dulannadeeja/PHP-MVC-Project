<?php

// import composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

// get namespaces from composer autoload
use App\controllers\SiteController;
use Dulannadeeja\Mvc\Application;
use App\controllers\AuthController;
use App\models\User;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// configuration
$config = [
    'userClass' => User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

// initialize application
$app = new Application(__DIR__, $config);

// define routes
$app->router->get('/', [SiteController::class,'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);
$app->router->get('/profile', [AuthController::class, 'profile']);

$app->db->applyMigrations();

// run application
$app->run();