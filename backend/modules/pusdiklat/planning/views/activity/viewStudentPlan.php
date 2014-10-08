<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use backend\models\Reference;

/* @var $this yii\web\View */
/* @var $model backend\models\Activity */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'View {modelClass}: ', [
    'modelClass' => 'Student Plan',
]) . ' ' . Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Inflector::camel2words($model->name), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'View Student Plan');
?>
<div class="activity-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		
		<table class="table table-striped">
		<?php
		$units = Reference::find()
			->select([
				'id','value'
			])
			->where('type=:type',[':type'=>'unit'])
			->all();
		foreach($units as $unit){
			if(strlen($unit->value)>1){
				echo "<tr>";
				echo "<td>".$unit->value."</td>";
				$student = 0;
				if(null!=$trainingStudentPlan){
					$student = (int)$trainingStudentPlan->getStudentCountByUnit($unit->id);
				}
				echo "<td>".$student."</td>";
				echo "</tr>";				
			}
		}
		?>
		</table>
		<button type="button" class="btn btn-info" data-dismiss="modal">
			Close
		</button>
	</div>
</div>
