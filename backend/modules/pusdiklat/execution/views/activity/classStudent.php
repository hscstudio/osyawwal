<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\execution\models\TrainingClassSubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Student Class #'. $class->class;
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
				'header' => '<div style="text-align:center;">Name</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->trainingStudent->student->person->name,[
						'class'=>'','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'header' => '<div style="text-align:center;">NIP</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->trainingStudent->student->person->nip,[
						'class'=>'label label-info','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'header' => '<div style="text-align:center;">Satker</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$student = $data->trainingStudent->student;
					$unit = $student->person->unit->reference->name;
					
					if($student->satker==2){
						if(!empty($student->eselon2)){
							$unit = $student->eselon2;
						}
					}
					else if($student->satker==3){
						if(!empty($student->eselon3)){
							$unit = $student->eselon3;
						}
					}
					else if($student->satker==4){
						if(!empty($student->eselon4)){
							$unit = $student->eselon4;
						}
					}
					return Html::tag('span',$unit,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'class' => 'kartik\grid\ActionColumn',
			]
           /*  
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
			], */
			
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

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="glyphicon glyphicon-upload"></i> Get Random Student
	</div>
    <div class="panel-body">
		<?php
		$form = \yii\bootstrap\ActiveForm::begin([
			'options'=>[
				'id'=>'myform'
			],
			'action'=>[
				'class-student','id'=>$activity->id,
				'class_id'=>$class->id
			], 
		]);
		?>
		<div class="row clearfix">
			<div class="col-md-2">
			<?php
			echo Html::beginTag('label',['class'=>'control-label']).'Stock'.Html::endTag('label');
			echo Html::input('text','',$trainingStudentCount,['class'=>'form-control','disabled'=>'disabled','id'=>'stock']);
			?>
			</div>
			<div class="col-md-2">
			<?php
			echo Html::beginTag('label',['class'=>'control-label']).'Jumlah'.Html::endTag('label');
			echo Html::input('text','student','',['class'=>'form-control','id'=>'count']);
			?>
			</div>
			<div class="col-md-3">
			<?php
			echo '<label class="control-label">Berdasarkan</label>';
			echo Select2::widget([
				'name' => 'baseon', 
				'data' => [
					'person.name' =>'Nama', 
					'person.gender' => 'Gender', 
					'object_reference.reference_id' => 'Unit',					
				],
				'options' => [
					'placeholder' => 'Select base on ...', 
					'class'=>'form-control', 
					'multiple' => true
				],
			]);
			?>
			</div>
			<div class="col-md-3">
			<?php
			echo Html::beginTag('label',['class'=>'control-label']).' '.Html::endTag('label');
			echo Html::submitButton('Get', ['class' => 'btn btn-success','style'=>'display:block;']);
			?>
			</div>
		</div>
		<?php \yii\bootstrap\ActiveForm::end(); ?>
		<?php
		$this->registerJs("
			$('#myform').on('beforeSubmit', function () {
				var x = $('#count').val().parseInt();
				var y = $('#stock').val().parseInt();
				if(y>=x & x>0){

				}				
				else{

					$('#count').select();	
					return false;					
				}	
			});
		");
		?>
	</div>
</div>
