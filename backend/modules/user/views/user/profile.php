<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Profile {modelClass}', [
    'modelClass' => 'User',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User'), 'url' => ['profile']];
?>
<div class="user-update panel panel-default">	
    <div class="panel-heading">		
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<?php
		$renders = [];
		$renders['model'] = $model;
		$renders['employee'] = $employee;
		$renders['person'] = $person;
		$renders['object_references_array'] = $object_references_array;
		foreach($object_references_array as $object_reference=>$label){;
			$renders[$object_reference] = ${$object_reference};
		}
		$renders['object_file_array'] = $object_file_array;
		foreach($object_file_array as $object_file=>$label){
			$renders[$object_file] = ${$object_file};
			$renders[$object_file.'_file'] = ${$object_file.'_file'};
		}
		?>
		<?= $this->render('_form', $renders) ?>
	</div>
</div>
