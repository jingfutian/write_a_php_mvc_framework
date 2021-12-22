<?php
use App\Core\Form\Form;
use App\Core\Form\TextareaField;

/**
 * @var \App\Core\View $this
 */
$this->title = 'Contact'

/**
 * @var \App\Models\ContactForm $model
 */
?>

<h1>Contact</h1>

<?php $form = Form::begin('', 'post') ?>
  <?php echo $form->field($model, 'subject') ?>
  <?php echo $form->field($model, 'email') ?>
  <?php echo new TextareaField($model, 'body') ?>
  <button type="submit" class="btn btn-primary mt-3">Submit</button>
<?php Form::end(); ?>