<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Activity */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Training Activity',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-create  panel panel-default">

    <div class="panel-heading"> 
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">
		<?php
		$renders=[];
		$renders['model'] = $model;
		$renders['training'] = $training;
		echo $this->render('_form', $renders) 
		?>
	</div>
</div>
