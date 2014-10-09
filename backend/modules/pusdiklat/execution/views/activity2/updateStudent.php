<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
/* @var $this yii\web\View */
/* @var $model backend\models\TrainingStudent */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Training Student',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="training-student-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<?php $form = ActiveForm::begin(); ?>
		<?= $form->errorSummary($training_student) ?> <!-- ADDED HERE -->
		<div class="row clearfix">
			<div class="col-md-2">
			<?= $form->field($training_student, 'status')->widget(SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'Active',
						'offText' => 'Cancel',
					]
				])->label('Status') ?>
			</div>
			<div class="col-md-10">
			<?= $form->field($training_student, 'note')->textInput(['maxlength' => 255])->label('Catatan'); ?>
			</div>
		</div>
		<div class="form-group">
			<?= Html::submitButton($training_student->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $training_student->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

		<?php ActiveForm::end(); ?>
		<?php $this->registerCss('label{display:block !important;}'); ?>
	</div>
</div>
