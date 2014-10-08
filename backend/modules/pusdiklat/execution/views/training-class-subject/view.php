<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\TrainingClassSubject */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Update #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Class Subjects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-class-subject-view  panel panel-default">

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
			<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
		            'id',
            'training_class_id',
            'program_subject_id',
            'status',
            'created',
            'created_by',
            'modified',
            'modified_by',
				],
			]) ?>
	</div>
</div>
