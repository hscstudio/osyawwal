<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'History of #'.Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
<div class="program-view">

	<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => [
		['class' => 'kartik\grid\SerialColumn'],
		[
			'label' => 'Rev',
			'attribute' => 'revision',
			'vAlign'=>'middle',
			'hAlign'=>'center',
			'width'=>'70px',
			'headerOptions'=>['class'=>'kv-sticky-column'],
			'contentOptions'=>['class'=>'kv-sticky-column'],
			'format'=>'raw',
			'value' => function ($data){
				return Html::tag('span',$data->revision,['class'=>'label label-default','data-toggle'=>'tooltip','title'=>'Revisi ke '.$data->revision.'']);
			},
		],
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
				$mp = \backend\models\ProgramSubjectHistory::find()
					->where([
						'program_id' => $data->id,
						'program_revision' =>$data->revision
					])
					->count();
				$icon='<span class="fa fa-fw fa-clipboard"></span> - '.$mp;
				return Html::a(
					$icon,
					['subject-history','id'=>$data->id,'revision'=>$data->revision],
					[
						'class'=>'modal-heart btn btn-default btn-xs',
						'modal-size' => 'modal-lg',
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
				return Html::a(
					$icon,
					['document-history','id'=>$data->id],
					[
						'class'=>'modal-heart btn btn-default btn-xs',
						'modal-size' => 'modal-lg',
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
				
				$object_person=\backend\models\ObjectPerson::find()
						->where([
							'object'=>'program',
							'object_id'=>$data->id,
							'type'=>'organisation_1213020200', //1213020200 CEK KD_UNIT_ORG 1213020200 IN TABLE ORGANISATION IS SUBBIDANG KURIKULUM
						])
						->one();
						
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
				return Html::tag('span', $icon, [
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
			'template' => '{view}',
			'width'=>'70px',
			'buttons' => [
				'view' => function ($url, $model) {
					$icon='<span class="fa fa-fw fa-eye"></span>';
					return Html::a($icon,['view-history','id'=>$model->id,'revision'=>$model->revision],
					['class'=>'btn btn-default btn-xs modal-heart',
					'data-pjax'=>"0",'title'=>'View History of '.$model->name,'modal-size'=>'modal-lg']);
				},
			],
		],
	],
	'panel' => [
		'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
		'before'=>	Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-warning']),
			
		'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
		'showFooter'=>false
	],
	'responsive'=>true,
	'hover'=>true,
]); ?>	
<?= \hscstudio\heart\widgets\Modal::widget() ?>
	
</div>
