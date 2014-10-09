<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\execution\models\TrainingClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Honorarium #'. Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-class-index">
	<?php
	Box::begin([
		'type'=>'small', // ,small, solid, tiles
		'bgColor'=>'purple', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
		'bodyOptions' => [],
		'icon' => 'fa fa-fw fa-money',
		'link' => ['dashboard','id'=>$model->id],
		'footerOptions' => [
			'class' => 'dashboard-hide',
		],
		'footer' => '<i class="fa fa-arrow-circle-left"></i> Back',
	]);
	?>
	<h3>Honorarium</h3>
	<p>Honorarium of Training</p>
	<?php
	Box::end();
	?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],		
			[
				'attribute' => 'class',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],	
			[
				'format' => 'raw',
				'label' => 'Persiapan',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model){
					return Html::a('Generate',
						\yii\helpers\Url::to(['prepare','tb_training_class_id'=>$model->id]),
						['class'=>'label label-default']);
				}
			],
			[
				'format' => 'raw',
				'label' => 'Mengajar',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model){
					return Html::a('Generate',
						\yii\helpers\Url::to(['training','tb_training_class_id'=>$model->id]),
						['class'=>'label label-default']);
				}
			],
			[
				'format' => 'raw',
				'label' => 'Transport',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model){
					return Html::a('Generate',
						\yii\helpers\Url::to(['transport','tb_training_class_id'=>$model->id]),
						['class'=>'label label-default']);
				}
			],            
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> Create ', ['create-class','id'=>$model->id], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>

</div>