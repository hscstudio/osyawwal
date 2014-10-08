<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use kartik\widgets\SwitchInput;
use backend\models\Person;
use backend\models\Employee;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	
	<?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'new_password')->passwordInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'role')->hiddenInput(['value'=>'1'])->label(false) ?>
	
	<?= $form->field($model, 'status')->widget(SwitchInput::classname(), [
		'pluginOptions' => [
			'onText' => 'Active',
			'offText' => 'Blocked',
		]
	]) ?> 
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	<?php $this->registerCss('label{display:block !important;}'); ?>
	
</div>
