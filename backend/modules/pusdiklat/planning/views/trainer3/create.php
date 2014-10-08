<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Trainer */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Trainer',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trainers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trainer-create  panel panel-default">
	<?php
	if (!Yii::$app->request->isAjax){ ?>
    <div class="panel-heading"> 
		<div class="pull-right">
        <?php
		echo Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['person'], ['class' => 'btn btn-xs btn-primary']) 
		?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<?php } ?>
	<div class="panel-body">
		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
	</div>
</div>
