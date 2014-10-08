<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProgramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Programs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="program-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Program',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
-->
	<?php
	$program_code = ArrayHelper::map(
		\backend\models\Reference::find()
			->select(['id','value', 'name'])
			->where(['type'=>'program_code'])
			->orderBy(['sort'=> SORT_ASC])			
			->asArray()
			->all(), 
			'value', 'name'
	);
	?>
	
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-program-gridview',
	]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            // 'id',
            
			[
				'attribute' => 'number',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'150px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data) use ($program_code){
					return Html::tag('span',$data->number,['class'=>'label label-default','data-toggle'=>'tooltip','title'=>$program_code[$data->number]]);
				},				
			],
		
			[
				'attribute' => 'name',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format'=>'raw',
				'value' => function ($data){
					return Html::a($data->name,'#',['title'=>$data->note,'data-toggle'=>"tooltip",'data-placement'=>"top"]);
				},
			],
		
			/* [
				'header' => 'Docs',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span','<i class="fa fa-book"></i>',['class'=>' label-','data-toggle'=>'tooltip','title'=>'Setara dengan '.$data->days.' Hari']);
				},
			], */
			
			[
				'label' => 'JP',
				'attribute' => 'hours',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->hours,['class'=>'label label-default','data-toggle'=>'tooltip','title'=>'Setara dengan '.$data->days.' Hari']);
				},
			],
		
			/* [
				'attribute' => 'test',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
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
			], */
			
			[
				'attribute' => 'validation_status',
				'label' => 'Validate',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$validation_icons = [
						'0'=>'<span class="glyphicon glyphicon-fire"></span>',
						'1'=>'<span class="glyphicon glyphicon-refresh"></span>',
						'2'=>'<span class="glyphicon glyphicon-check"></span>',
						'3'=>'<span class="glyphicon glyphicon-remove"></span>'
					];
					$validation_classes = ['0'=>'warning','1'=>'info','2'=>'success','4'=>'danger'];
					return Html::a(
						$validation_icons[$data->validation_status],
						['validation','id'=>$data->id],
						[
							'class'=>' modal-heart label label-'.$validation_classes[$data->validation_status],
							'data-toggle'=>'tooltip',
							'data-pjax'=>"0",
							'title'=>$data->validation_note,
							'modal-title'=>'Form Validation',
							'modal-size'=>'modal-lg',
						]
					);
				},
			],
			
			[
				'format' => 'raw',
				'label' => 'PIC',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){
					$object_person=\backend\models\ObjectPerson::find()
						->where([
							'object'=>'program',
							'object_id'=>$data->id,
							'type'=>'organisation_393', // CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
						])
						->one();
					if($object_person!=null){						
						return Html::a(substr($object_person->person->name,0,5).'.',['pic','id'=>$data->id],
							[
								'class'=>'label label-info modal-heart',
								'data-toggle'=>'tooltip',
								'data-pjax'=>'0',
								'data-html'=>'true',
								'title'=>'Current PIC Program : '.$object_person->person->name.'.<br> Click to change pic program',
								'modal-title'=>'Change PIC',
								'modal-size'=>'modal-md',
							]);								
					}
					else{
						return Html::a('-',['pic','id'=>$data->id],
							[
								'class'=>'label label-warning modal-heart',
								'data-toggle'=>'tooltip',
								'data-pjax'=>'0',
								'data-html'=>'true',
								'title'=>'Click to set PIC program',
								'modal-title'=>'Set PIC',
								'modal-size'=>'modal-md'
							]);
					}
				}
			],
			
			[
				'format' => 'raw',
				'attribute' => 'status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){
					$icon = ($data->status==1)?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
					return Html::a($icon, ['status','status'=>$data->status, 'id'=>$data->id], [
						'onclick'=>'
							$.pjax.reload({url: "'.\yii\helpers\Url::to(['status','status'=>$data->status, 'id'=>$data->id]).'", container: "#pjax-gridview", timeout: 3000});
							return false;
						',
						'class'=>($data->status==1)?'label label-info':'label label-warning',
					]);
					
				}
			],
			
            // 'stage',
            // 'category',
            // 'validation_status',
            // 'validation_note',
            // 'status',
            // 'created',
            // 'created_by',
            // 'modified',
            // 'modified_by',

            [
				'class' => 'kartik\grid\ActionColumn',
				'buttons' => [
					'view' => function ($url, $model) {
						$icon='<span class="glyphicon glyphicon-eye-open"></span>';
						return Html::a($icon,$url,['class'=>'modal-heart','data-pjax'=>"0",'title'=>$model->name,'modal-size'=>'modal-lg']);
					},
					'update' => function ($url, $model) {
						$icon='<span class="glyphicon glyphicon-pencil"></span>';
						return Html::a($icon,$url,['class'=>'modal-heart','data-pjax'=>"0",'title'=>$model->name,'modal-size'=>'modal-lg']);
					},
				],
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-plus"></i> Create ', ['create'], [
					'class' => 'btn btn-success modal-heart ',
					'data-toggle'=>'tooltip',
					'data-pjax'=>"0",
					'modal-title'=>'Create Program',
					'modal-size'=>'modal-lg',
				]).
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['1'=>'Published','0'=>'Unpublished','all'=>'All'],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.Url::to(['index']).'?status="+$(this).val(), 
								container: "#pjax-program-gridview", 
								timeout: 1,
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
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
	<?php /* $this->registerCss('.select2-container{width:125px !important;}'); */ ?>
	<?php \yii\widgets\Pjax::end(); ?>
	
</div>
