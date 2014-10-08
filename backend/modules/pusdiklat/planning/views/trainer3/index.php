<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\planning\models\TrainerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Trainers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trainer-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Trainer',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            
			[
				'attribute' => 'name',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					return Html::a($data->person->name,['./person/view','id'=>$data->person_id],
						['class'=>'modal-heart badge','title'=>$data->person->name,'source'=>'div.panel-body','data-toggle'=>'tooltip']);							
				}					
			],
			[
				'attribute' => 'phone',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					return Html::a($data->person->phone,['./person/view','id'=>$data->person_id],
						['class'=>'modal-heart badge','title'=>$data->person->phone,'source'=>'div.panel-body','data-toggle'=>'tooltip']);							
				}					
			],
			
			[
				'attribute' => 'organisation',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					return Html::a($data->person->organisation,['./person/view','id'=>$data->person_id],
						['class'=>'modal-heart badge','title'=>$data->person->organisation,'source'=>'div.panel-body','data-toggle'=>'tooltip']);							
				}					
			],
            [
				'class' => 'kartik\grid\ActionColumn',
				'width' => '125px',
				'buttons' => [
					'view' => function($url,$model,$key){
						$icon = '<i class="fa fa-fw fa-eye"></i>';
						return Html::a(
							$icon,
							['view-person','id'=>$model->person_id],
							[
								'class'=>'btn btn-default btn-xs',
								'title'=>'View',
								'data-pjax'=>'0',
								'data-toggle'=>'tooltip'
							]
						);							
					},
					'update' => function($url,$model,$key){
						$icon = '<i class="fa fa-fw fa-pencil"></i>';
						return Html::a(
							$icon,
							['update-person','id'=>$model->person_id],
							[
								'class'=>'btn btn-default btn-xs',
								'title'=>'View',
								'data-pjax'=>'0',
								'data-toggle'=>'tooltip'
							]
						);							
					},
					'delete' => function($url,$model,$key){
						$count = \backend\models\TrainingSubjectTrainerRecommendation::find()
							->where([
								'trainer_id'=>$model->person_id,
								
							])
							->count();
						if($count>0){
							return '';
						}
						else{
							$icon = '<i class="fa fa-fw fa-trash"></i>';
							return Html::a(
								$icon,
								$url,
								[
									'class'=>'btn btn-default btn-xs',
									'title'=>'View',
									'data-pjax'=>'0',
									'data-toggle'=>'tooltip',
									'data-method'=>'post',
									'data-confirm'=>'Apakah Anda yakin akan menghapus pengajar ini!'
								]
							);	
						}
					}
				]
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> Create ', ['person'], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>

</div>
