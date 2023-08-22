<?php
/** @var $this View */

use Dulannadeeja\Mvc\View;

$this->title = 'Register';
?>
<h1>Create an account</h1>
<?php use Dulannadeeja\Mvc\form\Form;

$form = Form::start('', 'post') ?>
<?php if (!empty($registerModel)): ?>
    <?php echo $form->inputField($registerModel, 'firstName', ['name' => 'firstName', 'type' => 'text', 'placeholder' => 'enter your first name.']) ?>
    <?php echo $form->inputField($registerModel, 'lastName', ['name' => 'lastName', 'type' => 'text', 'placeholder' => 'enter your last name.']) ?>
    <?php echo $form->inputField($registerModel, 'email', ['name' => 'email', 'type' => 'email', 'placeholder' => 'enter your email.']) ?>
    <?php echo $form->inputField($registerModel, 'password', ['name' => 'password', 'type' => 'password', 'placeholder' => 'enter your password.']) ?>
    <?php echo $form->inputField($registerModel, 'confirmPassword', ['name' => 'confirmPassword', 'type' => 'password', 'placeholder' => 'confirm your password.']) ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php endif; ?>
<?php echo Form::end() ?>