<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Trainer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="trainer-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	<?= Html::activeHiddenInput($model, 'person_id') ?>
	<?php 
	if(!$model->isNewRecord){ ?>
		<?= $form->field($model, 'category')->textInput(['maxlength' => 255]) ?>
		<?= $form->field($model, 'education_history')->textarea(['rows' => 6]) ?>
		<?= $form->field($model, 'training_history')->textarea(['rows' => 6]) ?>
		<?= $form->field($model, 'experience_history')->textarea(['rows' => 6]) ?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	<?php 
	} 
	else{ ?>
		<div class="jumbotron">
			<h1>Apakah Anda yakin</h1>
			<p class="lead">"<strong><?= $model->person->name; ?></strong>" akan dijadikan sebagai pengajar</p>
			<p>
			<?= Html::submitButton(Yii::t('app', 'Ya saya yakin!'), ['class' => 'btn btn-success btn-md']) ?>
			</p>
		</div>
	<?php 
	} 
	?>
    

    <?php ActiveForm::end(); ?>

</div>
