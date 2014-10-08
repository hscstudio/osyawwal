<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;
/* @var $this yii\web\View */
/* @var $model backend\models\Activity */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Property #'. Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-view  panel panel-default">
	
	<?php
	Box::begin([
		'type'=>'small', // ,small, solid, tiles
		'bgColor'=>'red', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
		'bodyOptions' => [],
		'icon' => 'glyphicon glyphicon-eye-open',
		'link' => ['dashboard','id'=>$model->id],
		'footer' => '<i class="fa fa-arrow-circle-left"></i> Back',
	]);
	?>
	<h3>Property</h3>
	<p>Property of Training</p>
	<?php
	Box::end();
	?>
	<div class="panel-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#training" role="tab" data-toggle="tab">Training <span class="label label-primary">1</span> </a></li>
			<li><a href="#program" role="tab" data-toggle="tab">Program <span class="label label-warning">2</span> </a></li>
		</ul>
		<!-- Tab panes -->	
		<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:5px; background-color: #fff;">
			<div class="tab-pane fade-in active" id="training">
				<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
		            'id',
					'satker_id',
					'name',
					'description:ntext',
					'start',
					'end',
					'location',
					'hostel',
					'status',
					'created',
					'created_by',
					'modified',
					'modified_by',
					],
				]) ?>
			</div>
			<div class="tab-pane fade" id="program">
				<?= DetailView::widget([
				'model' => $program,
				'attributes' => [
		            'id',
					'number',
					'name',
					'hours',
					'days',
					'test',
					'note',
					'stage',
					'category',
					'validation_status',
					'validation_note',
					'status',
					'created',
					'created_by',
					'modified',
					'modified_by',
					],
				]) ?>
			</div>
		</div>

			
	</div>
</div>
