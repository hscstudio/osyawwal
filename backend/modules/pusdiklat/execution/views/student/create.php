<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Student */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Student',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-create  panel panel-default">
	<?php
	if (!Yii::$app->request->isAjax){ ?>
    <div class="panel-heading"> 
		<div class="pull-right">
        <?= Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<?php } ?>
	<div class="panel-body">
		<?php $form = ActiveForm::begin(); ?>
		<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
		<?= Html::activeHiddenInput($model, 'person_id') ?>
		<?php 
		if($model->isNewRecord){ ?>
			<div class="jumbotron">
				<h1>Apakah Anda yakin</h1>
				<p class="lead">"<strong><?= $model->person->name; ?></strong>" akan dijadikan sebagai peserta</p>
				<p>
				<?= Html::submitButton(Yii::t('app', 'Ya saya yakin!'), ['class' => 'btn btn-success btn-md']) ?>
				</p>
			</div>
		<?php 
		} 
		?>
		

		<?php ActiveForm::end(); ?>
	</div>
</div>
