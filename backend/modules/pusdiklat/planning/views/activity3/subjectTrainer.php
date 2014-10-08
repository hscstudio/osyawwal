<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\planning\models\TrainingSubjectTrainerRecommendationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Trainer Recommendation : '.$program_subject->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subject : '.Inflector::camel2words($model->name)), 'url' => ['subject','id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-subject-trainer-recommendation-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Training Subject Trainer Recommendation',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
				'attribute' => 'type',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->reference->name,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
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
					return Html::tag('span',
						$data->trainer->person->name.' - '.
						$data->trainer->person->phone
						,
						[
							'class'=>'','data-toggle'=>'tooltip','title'=>
								'Organisation : '.$data->trainer->person->organisation.'<br>'.
								'Email : '.$data->trainer->person->email.'<br>'
							,'data-html'=>'true',
						]
					);
				},
			],
			[
				'attribute' => 'note',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',substr($data->note,0,25),[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>$data->note
					]);
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
            // 'sort',
            // 'status',
            // 'created',
            // 'created_by',
            // 'modified',
            // 'modified_by',

            [
				'class' => 'kartik\grid\ActionColumn',
				'width' => '125px',
				'buttons' => [
					'view' => function($url,$model,$key){
						$icon = '<i class="fa fa-fw fa-eye"></i>';
						return Html::a(
							$icon,
							['view-subject-trainer','id'=>$model->id],
							[
								'class'=>'modal-heart btn btn-default btn-xs',
								'title'=>'View Trainer Recomendation',
								'data-pjax'=>'0',
								'data-toggle'=>'tooltip',
								'modal-title'=>'View Trainer Recomendation',
								'modal-size'=>'modal-md',
							]
						);							
					},
					'update' => function($url,$model,$key){
						$icon = '<i class="fa fa-fw fa-pencil"></i>';
						return Html::a(
							$icon,
							['update-subject-trainer','id'=>$model->id],
							[
								'class'=>'btn btn-default btn-xs',
								'title'=>'View',
								'data-pjax'=>'0',
								'data-toggle'=>'tooltip'
							]
						);							
					},
					'delete' => function($url,$model,$key){
						$icon = '<i class="fa fa-fw fa-trash"></i>';
						return Html::a(
							$icon,
							['delete-subject-trainer','id'=>$model->id],
							[
								'class'=>'btn btn-default btn-xs',
								'title'=>'View',
								'data-pjax'=>'0',
								'data-toggle'=>'tooltip',
								'data-method'=>'post',
								'data-confirm'=>'Apakah Anda yakin akan menghapus data rekomendasi pengajar ini!'
							]
						);	
					}
				]
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-circle-left"></i> Back', Url::to(['subject','id'=>$model->id]), ['class' => 'btn btn-warning','data-pjax'=>'0']).' '.
				Html::a('<i class="fa fa-fw fa-plus-circle"></i> Recommendation', ['choose-trainer','id'=>$model->id,'subject_id'=>$program_subject->id], ['class' => 'btn btn-success','data-pjax'=>'0','onclick'=>"$('#div_formX').slideToggle('slow');return true;"]).' ',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
</div>
