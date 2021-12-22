<h1>Login Page</h1>

<?php $form = App\Core\Form\Form::begin('', 'POST'); ?>
  <?php echo $form->field($model, 'email') ?>
  <?php echo $form->field($model, 'password')->passwordField() ?>

  <button type="submit" class="btn btn-primary mt-3">Submit</button>
<?php App\Core\Form\Form::end(); ?>