<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Trainer */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Update #'.$model->person_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trainers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trainer-view  panel panel-default">

   <div class="panel-heading"> 
		<div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">

		<!--
		<p>
			<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->person_id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->person_id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
					'method' => 'post',
				],
			]) ?>
		</p>
		-->
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
		            'person_id',
            'category',
            'education_history:ntext',
            'training_history:ntext',
            'experience_history:ntext',
				],
			]) ?>
	</div>
</div>
