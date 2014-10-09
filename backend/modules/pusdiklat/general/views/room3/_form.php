<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
/* @var $this yii\web\View */
/* @var $model backend\models\Room */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="room-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	
	<div class="row clearfix">
		<div class="col-md-4">
		<?= $form->field($model, 'code')->textInput(['maxlength' => 25]) ?>
		</div>
		<div class="col-md-8">
		<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-2">
		<?= $form->field($model, 'capacity')->textInput() ?>
		</div>
		<div class="col-md-2">
		<?= $form->field($model, 'computer')->widget(SwitchInput::classname(), [
			'pluginOptions' => [
				'onText' => 'On',
				'offText' => 'Off',
			]
		]) ?>
		</div>
		<div class="col-md-2">
		<?= $form->field($model, 'hostel')->widget(SwitchInput::classname(), [
			'pluginOptions' => [
				'onText' => 'On',
				'offText' => 'Off',
			]
		]) ?>
		</div>
		<div class="col-md-2">
		<?= $form->field($model, 'owner')->widget(SwitchInput::classname(), [
			'pluginOptions' => [
				'onText' => 'On',
				'offText' => 'Off',
			]
		]) ?>
		</div>
	</div>
    

    <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'status')->widget(SwitchInput::classname(), [
		'pluginOptions' => [
			'onText' => 'On',
			'offText' => 'Off',
		]
	]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	
	<?php
	/* if($model->isNewRecord){
		$initScript = '
		//$("#trainer-ref_religion_id").select2().select2("val", 0);
		$("#room-owner").bootstrapSwitch("state", true, true);
		$("#room-status").bootstrapSwitch("state", true, true);

		';
		$this->registerJS($initScript);
	}	*/
	$this->registerCss('label{display:block !important;}'); 
	?>
</div>
