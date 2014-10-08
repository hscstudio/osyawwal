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
    'modelClass' => 'Training',
]) . ' ' . Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Subject');
?>
<div class="program-update panel panel-default">

	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview',
	]); ?>
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
			'header' => Html::tag('span','Trainer',[
				'data-toggle'=>'tooltip',
				'title'=>'Tenaga Pengajar',
			]),
			'vAlign'=>'middle',
			'hAlign'=>'center',
			'width'=>'70px',
			'headerOptions'=>['class'=>'kv-sticky-column'],
			'contentOptions'=>['class'=>'kv-sticky-column'],
			'format'=>'raw',
			'value' => function ($data) use ($model){
				$mp = \backend\models\TrainingSubjectTrainerRecommendation::find()
					->where([
						'training_id' => $model->id,
						'program_subject_id' => $data->id,
						'status' => 1,
					])
					->count();
				$icon='<span class="fa fa-fw fa-user-md"></span> - '.$mp;
				return Html::a(
					$icon,
					['subject-trainer','id'=>$model->id,'subject_id'=>$data->id],
					[
						'class'=>'btn btn-default btn-xs',
						'data-toggle'=>'tooltip',
						'data-pjax'=>'0',
						'title'=>'Tenaga Pengajar',
					]
				);
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
		'before'=>
			Html::a('<i class="fa fa-fw fa-arrow-circle-left"></i> Back', Url::to(['index']), ['class' => 'btn btn-warning','data-pjax'=>'0']).' '			
			,
		'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
		'showFooter'=>false
	],
	'responsive'=>true,
	'hover'=>true,
	]); ?>	
	
	<?php \yii\widgets\Pjax::end(); ?>
	<?php 
	$this->registerCss('label{display:block !important;}'); 	
	/* if (!in_array(Yii::$app->request->get('action'),['update','create'])){
		$this->registerJs('
			$("#div_form").slideToggle("slow");
		');
	} 	 */
	?>
</div>
