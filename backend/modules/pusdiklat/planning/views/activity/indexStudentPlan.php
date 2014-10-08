<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use backend\models\Reference;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\planning\models\ActivitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Student Plan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Activity',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
-->
	<?php
	$grid_columns = [
		['class' => 'kartik\grid\SerialColumn'],        
		[
			'attribute' => 'name',
			'vAlign'=>'middle',
			'hAlign'=>'left',
			'headerOptions'=>['class'=>'kv-sticky-column','style'=>'height:100px;'],
			'contentOptions'=>['class'=>'kv-sticky-column'],	
			'format'=>'raw',
			'value' => function ($data){
				return 
				Html::a($data->name,'#',[
					'title'=>$data->description.'<hr>'.$data->training->note,
					'data-toggle'=>"tooltip",
					'data-placement'=>"top",
					'data-html'=>'true',
				]).'<br>'.
				Html::tag('span',date('d M Y',strtotime($data->start)),[
					'class'=>'label label-info',
				])
				.' s.d '.
				Html::tag('span',date('d M Y',strtotime($data->end)),[
					'class'=>'label label-info',
				]);
			},
		],            
	];
	
	$units = Reference::find()
		->select([
			'id','value'
		])
		->where('type=:type',[':type'=>'unit'])
		->all();
	$idx=0;
	foreach($units as $unit){
		if(strlen($unit->value)>1){
			$grid_columns[]=[
				'format'=>'raw',
				'width' => '30px',
				'vAlign'=>'left',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'header' => '<div class="rotate" style="width:25px;">'.$unit->value.'</div>',
				'value' => function ($data) use ($unit){
					$trainingStudentPlan = $data->training->trainingStudentPlan;
					if(null!=$trainingStudentPlan){	
						$student = (int) $trainingStudentPlan->getStudentCountByUnit($unit->id);
						if($student>0){
							return '<div class="label  label-success">'.$student.'</div>';
						}
						else{
							return '';
						}
					}
					else{ 
						return '<div class="label alert-success">'.'-'.'</div>';
					}
				}
			];
			$idx++;
		}
	}
	
	$grid_columns[] = [
		'label' => 'Total',
		'vAlign'=>'left',
		'hAlign'=>'center',
		'width' => '30px',
		'headerOptions'=>['class'=>'kv-sticky-column'],
		'header' => '<div class="rotate" style="width:25px;">Total</div>',
		'contentOptions'=>['class'=>'kv-sticky-column'],
		'format'=>'raw',
		'value' => function ($data){
			return '<div class="label label-primary">'.$data->training->student_count_plan.'</div>';
		},
	];	
	
	$grid_columns[] = [
		'attribute' => 'status',
		'label' => 'Status',
		'vAlign'=>'left',
		'hAlign'=>'center',
		'width' => '30px',
		'headerOptions'=>['class'=>'kv-sticky-column'],
		'header' => '<div class="rotate" style="width:25px;">Status</div>',
		'contentOptions'=>['class'=>'kv-sticky-column'],
		'format'=>'raw',
		'value' => function ($data){
			$status_icons = [
				'0'=>'<span class="glyphicon glyphicon-fire"></span>',
				'1'=>'<span class="glyphicon glyphicon-refresh"></span>',
				'2'=>'<span class="glyphicon glyphicon-check"></span>',
				'3'=>'<span class="glyphicon glyphicon-remove"></span>'
			];
			$status_classes = ['0'=>'warning','1'=>'info','2'=>'success','4'=>'danger'];
			$status_title = ['0'=>'Plan','1'=>'Ready','2'=>'Execution','4'=>'Cancel'];
			return Html::a(
				$status_icons[$data->status],
				['status','id'=>$data->id],
				[
					'class'=>' modal-heart label label-'.$status_classes[$data->status],
					'data-toggle'=>'tooltip',
					'data-pjax'=>"0",
					'title'=>$status_title[$data->status],
					'modal-title'=>'Form Validation',
					'modal-size'=>'modal-lg',
				]
			);
		},
	];	
	
	$grid_columns[] = [
		'class' => 'kartik\grid\ActionColumn',
		'template' => '{update} {view}',
		'buttons' => [
			'update' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-pencil"></span>';
						return ($model->status==2)?'':Html::a($icon,['update-student-plan','id'=>$model->id],[
							'class'=>'btn btn-default btn-xs modal-heart',
							'data-toggle'=>'tooltip',
							'data-pjax'=>'0',
							'data-html'=>'true',
							'title'=>'Klik untuk mengupdate rencana sebaran peserta',
							'modal-title'=>'Update Student Plan',
							'modal-size'=>'modal-md',
						]);
					},
			'view' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-eye"></span>';
						return ($model->status==2)?'':Html::a($icon,['view-student-plan','id'=>$model->id],[
							'class'=>'btn btn-default btn-xs modal-heart',
							'data-toggle'=>'tooltip',
							'data-pjax'=>'0',
							'data-html'=>'true',
							'title'=>'Klik untuk melihat rencana sebaran peserta',
							'modal-title'=>'View Student Plan',
							'modal-size'=>'modal-md',
						]);
					},
		],						
	];
	?>
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview',
	]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $grid_columns,
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-warning']).
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'year', 
					'data' => $year_training,
					'value' => $year,
					'options' => [
						'placeholder' => 'Year ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index-student-plan']).'?status='.$status.'&year="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>'.
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['nocancel'=>'All -Cancel','all'=>'All','0'=>'Plan','1'=>'Ready','2'=>'Execute','3'=>'Cancel'],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index-student-plan']).'?year='.$year.'&status="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1000,
							});
						',	
					],
				]).
				'</div>',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?php /* $this->registerCss('.select2-container{width:125px !important;}'); */ ?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
	<?php \yii\widgets\Pjax::end(); ?>
</div>
