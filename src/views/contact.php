<?php
/** @var $this View */

use Dulannadeeja\Mvc\View;

$this->title = 'Contact us';
?>
<h1>Contact us</h1>
<?php use Dulannadeeja\Mvc\form\Form;

$form = Form::start('', 'post') ?>
<?php if (!empty($contactModel)): ?>
    <?php echo $form->inputField($contactModel, 'subject', ['name' => 'subject', 'type' => 'text', 'placeholder' => 'enter your subject.']) ?>
    <?php echo $form->inputField($contactModel, 'email', ['name' => 'email', 'type' => 'email', 'placeholder' => 'enter your email.']) ?>
    <?php echo $form->TextAreaField($contactModel, 'body', ['name' => 'body', 'type' => 'text', 'placeholder' => 'enter your message.']) ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php endif; ?>
<?php echo Form::end() ?>
