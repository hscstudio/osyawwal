<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TrainingClassSubject */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Training Class Subject',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Class Subjects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-class-subject-create  panel panel-default">

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
