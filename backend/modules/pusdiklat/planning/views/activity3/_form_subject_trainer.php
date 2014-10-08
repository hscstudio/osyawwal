<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use backend\models\Reference;
/* @var $this yii\web\View */
/* @var $model backend\models\TrainingSubjectTrainerRecommendation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="training-subject-trainer-recommendation-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	
    <?php
	$data = ArrayHelper::map(Reference::find()
		->select(['id', 'name'])
		->where(['type'=>'trainer_type'])
		->asArray()
		->all()
		, 'id', 'name');
	echo $form->field($model, 'type')->widget(Select2::classname(), [
		'data' => $data,
		'options' => [
			'placeholder' => 'Choose type ...',
		],
		'pluginOptions' => [
			'allowClear' => true,
		],
	]);
	?>    

    <?= $form->field($model, 'note')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'status')->widget(SwitchInput::classname(), [
		'pluginOptions' => [
			'onText' => 'Publish',
			'offText' => 'Draft',
		]
	]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	<?php $this->registerCss('label{display:block !important;}'); ?>
</div>
