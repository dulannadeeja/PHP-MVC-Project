<?php

use Dulannadeeja\Mvc\Application;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="/">Home</a>
                <a class="nav-link" href="/contact">Contact</a>
                <a class="nav-link" href="#">Pricing</a>
            </div>
            <div class="navbar-nav ms-auto">
                <?php if (Application::isGuest()): ?>
                    <a class="nav-link" href="/login">Login</a>
                    <a class="nav-link" href="/register">Register</a>
                <?php else: ?>
                    <a class="nav-link" href="/profile"><?php echo ucfirst(Application::$app->user->getUserDisplayName()) ?></a>
                    <a class="nav-link" href="/logout">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!--show flash messages-->
<?php if (Application::$app->session->getFlash('success')) :?>
    <div class="alert alert-success">
        <?php echo Application::$app->session->getFlash('success'); ?>
    </div>
<?php endif; ?>

<div class="container">
    {{content}}
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
</body>
</html>