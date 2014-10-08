<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\execution\models\TrainingClassSubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Class #'. $class->class;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Inflector::camel2words($activity->name), 'url' => ['class','id'=>$activity->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-class-subject-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Training Class Subject',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
				'label' => 'Type',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$programSubject= \backend\models\ProgramSubject::find()
					->where([
						'id' => $data->program_subject_id,
						'status'=>1
					])
					->one();
					if(null!=$programSubject){
						return Html::tag('span',$programSubject->reference->name,[
							'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
						]);
					}
				},
			],
			[
				'header' => '<div style="text-align:center">Name</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$programSubject= \backend\models\ProgramSubject::find()
					->where([
						'id' => $data->program_subject_id,
						'status'=>1
					])
					->one();
					if(null!=$programSubject){
						return Html::tag('span',$programSubject->name,[
							'class'=>'','data-toggle'=>'tooltip','title'=>''
						]);
					}
				},
			],
			[
				'label' => 'hours',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$programSubject= \backend\models\ProgramSubject::find()
					->where([
						'id' => $data->program_subject_id,
						'status'=>1
					])
					->one();
					if(null!=$programSubject){
						return Html::tag('span',$programSubject->hours,[
							'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
						]);
					}
				},
			],
			
			[
				'label' => 'test',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$programSubject= \backend\models\ProgramSubject::find()
					->where([
						'id' => $data->program_subject_id,
						'status'=>1
					])
					->one();
					if(null!=$programSubject){						
						$icon = ($programSubject->test==1)?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';		
						return Html::tag('span', $icon, [
							'class'=>($programSubject->test==1)?'label label-info':'label label-warning',
							'title'=>' '.(($programSubject->status==1)?'Ujian':'Tanpa Ujian'),
							'data-toggle'=>'tooltip',
						]);
					}
				},
			],
			
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-circle-left"></i> Back ', ['class','id'=>$activity->id], ['class' => 'btn btn-warning']).' ',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>

</div>
