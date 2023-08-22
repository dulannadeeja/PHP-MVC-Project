<?php
/** @var $this View */

use Dulannadeeja\Mvc\View;

$this->title = 'Login';
?>
<h1>Login to the account</h1>
<?php use Dulannadeeja\Mvc\form\Form;

$form = Form::start('', 'post') ?>
<?php if (!empty($loginModel)): ?>
    <?php echo $form->inputField($loginModel, 'email', ['name' => 'email', 'type' => 'email', 'placeholder' => 'enter your email.']) ?>
    <?php echo $form->inputField($loginModel, 'password', ['name' => 'password', 'type' => 'password', 'placeholder' => 'enter your password.']) ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php endif; ?>
<?php echo Form::end() ?>