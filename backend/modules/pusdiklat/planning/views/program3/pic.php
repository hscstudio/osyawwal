<?php

use yii\helpers\Html;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'PIC {modelClass}: ', [
    'modelClass' => 'Program',
]) . ' ' . Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Inflector::camel2words($model->name), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'PIC');
?>
<div class="program-update panel panel-default">
	<?php
	if (!Yii::$app->request->isAjax) {
	?>
    <div class="panel-heading">		
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<?php
	}
	?>
	<div class="panel-body">
		<?php
		$renders = [];
		$renders['model'] = $model;
		$renders['object_people_array'] = $object_people_array;
		foreach($object_people_array as $object_person=>$label){
			$renders[$object_person] = ${$object_person};
		}
		?>
		<?= $this->render('_form_pic', $renders) ?>
	</div>
</div>
