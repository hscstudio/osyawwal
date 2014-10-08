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
				'header' => Html::tag('span','MP',[
					'data-toggle'=>'tooltip',
					'title'=>'Mata Pelajaran',
				]),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$mp = \backend\models\ProgramSubject::find()
						->where([
							'program_id' => $data->id,
							'status' => 1,
						])
						->count();
					$icon='<span class="fa fa-fw fa-clipboard"></span> - '.$mp;
					return Html::a(
						$icon,
						['subject','id'=>$data->id],
						[
							'class'=>'btn btn-default btn-xs',
							'data-toggle'=>'tooltip',
							'data-pjax'=>'0',
							'title'=>'Mata Pelajaran',
						]
					);
				},
			],
			
			[
				'label' => 'Docs',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$docs = \backend\models\ObjectFile::find()
						->joinWith('file')
						->where([
							'object'=>'program',
							'object_id' => $data->id,
							'type' => ['kap','gbpp','module'],
							'status' => 1,
						])
						->count();
					$icon='<span class="fa fa-fw fa-file-text"></span> - '.$docs;
					return Html::tag('span',
						$icon,
						[
							'class'=>'label label-default',
							'data-toggle'=>'tooltip',
							'data-pjax'=>'0',
							'title'=>'Program Document',
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
					// CEK AUTHORISE ACCESS
					$permit = \hscstudio\heart\helpers\Heart::OrganisationAuthorized(
						[
							'1213020300', // CEK KD_UNIT_ORG 1213020300 IN TABLE ORGANISATION IS TP
							'1213020000', // BIDANG RENBANG
							'1213000000', // PUSDIKLAT
						],
						[
							1, // 1= HEAD OF KD_UNIT_ORG
						]
					);
					
					$object_person=\backend\models\ObjectPerson::find()
							->where([
								'object'=>'program',
								'object_id'=>$data->id,
								'type'=>'organisation_1213020300', //1213020300 CEK KD_UNIT_ORG 1213020300 IN TABLE ORGANISATION IS SUBBIDANG TP
							])
							->one();
							
					if($permit){						
						if($object_person!=null){						
							return Html::a(substr($object_person->person->name,0,5).'.',['pic','id'=>$data->id],
								[
									'class'=>'label label-info modal-heart',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>'CURRENT PIC PROGRAM <br> '.$object_person->person->name.'.<br> CLICK TO SET PIC PROGRAM',
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
									'title'=>'CLICK TO SET PIC PROGRAM',
									'modal-title'=>'Set PIC',
									'modal-size'=>'modal-md'
								]);
						}
					}
					else{
						if($object_person!=null){						
							return Html::tag('span',substr($object_person->person->name,0,5).'.',
								[
									'class'=>'label label-info',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>'CURRENT PIC PROGRAM <br> '.$object_person->person->name,
								]);								
						}
						else{
							return Html::tag('span','-',
								[
									'class'=>'label label-warning',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>'PIC PROGRAM IS UNSET'
								]);
						}
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
					return Html::a($icon, '#', [
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
				'template' => '{view} {update}',
				'buttons' => [
					'view' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-eye"></span>';
						return Html::a($icon,$url,['class'=>'btn btn-default btn-xs modal-heart','data-pjax'=>"0",'title'=>$model->name,'modal-size'=>'modal-lg']);
					},
					'update' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-pencil"></span>';
						return Html::a($icon,$url,['class'=>'btn btn-default btn-xs modal-heart','data-pjax'=>"0",'title'=>$model->name,'modal-size'=>'modal-lg']);
					},
				],
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>				
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
