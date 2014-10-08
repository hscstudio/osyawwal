<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Trainer */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Trainer',
]) . ' ' . $model->person_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trainers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->person_id, 'url' => ['view', 'id' => $model->person_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="trainer-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
	</div>
</div>
