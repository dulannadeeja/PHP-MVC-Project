<?php
/** @var $this View */

use Dulannadeeja\Mvc\View;

$this->title = 'Home';
?>
<h1>Home</h1>
<?php use Dulannadeeja\Mvc\Application;

if (Application::isGuest()): ?>
    <h2>Welcome, Guest, Please register or login to your account!</h2>
<?php else: ?>
    <h2>Welcome, <?php echo Application::$app->user->getUserDisplayName() ?></h2>
<?php endif; ?>