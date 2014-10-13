<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use kartik\grid\GridView;
use backend\models\Reference;
/* @var $this yii\web\View */
/* @var $model backend\models\Program */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Subject {modelClass}: ', [
    'modelClass' => 'Program',
]) . ' ' . Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Subject');
?>
<div class="program-update">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		//'filterModel' => $searchModel,
		'columns' => [
			['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute' => 'type',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->reference->name,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			'name',
			[
				'attribute' => 'hours',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->hours,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			
			[
				'attribute' => 'test',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					if($data->test==1) {
						$icon='<span class="glyphicon glyphicon-check"></span>';
						return Html::a($icon,'#',['class'=>'label label-default','data-toggle'=>'tooltip','data-pjax'=>"0",'title'=>'Diklat dengan Ujian Akhir']);
					}
					else{
						$icon='<span class="glyphicon glyphicon-minus"></span>';
						return Html::a($icon,'#',['class'=>'badge','data-toggle'=>'tooltip','data-pjax'=>"0",'title'=>'Diklat tanpa Ujian Akhir']);
					}
				},
			],
			
			[
				'attribute' => 'sort',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->sort,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'label' => 'Status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){			
					$icon = ($data->status==1)?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';		
					return Html::tag('span', $icon, [
						'class'=>($data->status==1)?'label label-info':'label label-warning',
						'title'=>'Current status is '.(($data->status==1)?'publish':'draft'),
						'data-toggle'=>'tooltip',
					]);
				},
			],
		],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
	]); ?>	
	<?php 
	$this->registerCss('label{display:block !important;}'); 	
	?>
	</div>
</div>
