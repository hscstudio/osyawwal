<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Dropdown;
use kartik\widgets\Select2;
use backend\models\Activity;
use yii\helpers\Url;

/* @var $searchModel backend\models\ActivityRoomSearch */

$this->title = 'Activity #'.$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rooms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="activity-room-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview',
	]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],            		
			[
				'attribute' => 'activity_id',
				'format'=>'html',
				'label'=>'Activity',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value'=>  function ($data){
					$show='';
					$model = Activity::findOne($data->activity_id);
					$show = $model->name;
					if($model->satker_id!=Yii::$app->user->identity->employee->satker_id){
						$show.='<br><span class="label label-default">'.$model->satker->name.'</span>';
					}
					return $show;					
				}				
			],		
			[
				'attribute' => 'start',
				'vAlign'=>'middle',
				'format' => 'raw',
				'hAlign'=>'center',
				'width'=>'175px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){	
					return '<span class="label label-default">'.date('d-M-Y H:i:s',strtotime($data->start)).'</span>';
				}
			],
		
			[
				'attribute' => 'end',
				'format' => 'raw',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'175px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){	
					return '<span class="label label-default">'.date('d-M-Y H:i:s',strtotime($data->end)).'</span>';
				}
			],
		
			[
				'format' => 'raw',
				'attribute' => 'status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){									
					if ($data->status==1){
						$label='label label-info';
						$title='Process';
					}	
					else if ($data->status==2){ 
						$label='label label-success';
						$title='Approved';
					}
					else if ($data->status==3){ 
						$label='label label-danger';
						$title='Rejected';
					}
					else {
						$label='label label-warning';
						$title='Waiting';
					}
					return Html::tag('span', $title, ['class'=>$label,'title'=>$data->note,'data-toggle'=>"tooltip",'data-placement'=>"top",'style'=>'cursor:pointer']);
				}
			],

            [
				'class' => 'kartik\grid\ActionColumn',
				'template'=>'{update}',
				'buttons'=>[
					'update' => function($url, $data){
						$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
						$icon='<span class="fa fa-pencil"></span>';
						$url = \yii\helpers\Url::to([
							'update-activity-room',
							'id'=>$data->room_id,
							'activity_id' => $data->activity_id,
						]);
						return Html::a($icon,$url,['class'=>'btn btn-default btn-xs','data-pjax'=>'0',]);							
					},
				]
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.$model->satker->name.'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index','satker_id'=>$model->satker_id], ['class' => 'btn btn-warning']).' '.
				//Html::a('<i class="fa fa-fw fa-plus"></i> Create Activity Room', ['create'], ['class' => 'btn btn-success']).
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['all'=>'-- All --','0'=>'Waiting','1'=>'Process','2'=>'Approved','3'=>'Rejected'],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.Url::to(['activity-room']).'?id='.$model->id.'&status="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1000,
							});
						',	
					],
				]).
				'</div>',
			'after'=>
			Html::a('<i class="fa fa-fw fa-calendar"></i> Calendar', ['calendar-activity-room','id'=>$model->id,'status'=>$status], ['class' => 'btn btn-warning','data-pjax'=>0]).' '.
			Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', ['activity-room','id'=>$model->id], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?php \yii\widgets\Pjax::end(); ?>
	<?php 
	$this->registerCss('.select2-container { width: 200px !important; }');
	?>
	

</div>
