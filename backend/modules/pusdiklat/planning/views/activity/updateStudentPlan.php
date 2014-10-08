<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use backend\models\Reference;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Activity */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Student Plan',
]) . ' ' . Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Inflector::camel2words($model->name), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update Student Plan');
?>
<div class="activity-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<?php $form = ActiveForm::begin(); ?>
		<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
		
		<div class="row clearfix">
		<?php
		$units = Reference::find()
			->select([
				'id','value'
			])
			->where('type=:type',[':type'=>'unit'])
			->all();
		$idx=0;
		foreach($units as $unit){
			if(strlen($unit->value)>1){
				echo "<div class='col-md-3'>";
				$student = 0;
				if(null!=$trainingStudentPlan){
					$student = (int)$trainingStudentPlan->getStudentCountByUnit($unit->id);
				}
				echo $form->field($trainingStudentPlan, 'spread['.$unit->id.']',['template' => '<label class="control-label">'.$unit->value.'</label>{input}'])->textInput(['value'=>$student]);
				$idx++;
				echo "</div>";				
			}
		}
		?>
		</div>
		<?= Html::submitButton(
		$model->isNewRecord ? '<span class="fa fa-fw fa-save"></span> '.'Create' : '<span class="fa fa-fw fa-save"></span> '.'Update', 
		['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		<?php ActiveForm::end(); ?>
		<?php $this->registerCss('label{display:block !important;}'); ?>
	</div>
</div>
