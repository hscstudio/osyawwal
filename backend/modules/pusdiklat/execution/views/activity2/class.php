<?php

use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;
use kartik\widgets\Select2;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Class #'. Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-class-index">
	<?php
	Box::begin([
		'type'=>'small', // ,small, solid, tiles
		'bgColor'=>'yellow', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
		'bodyOptions' => [],
		'icon' => 'glyphicon glyphicon-home',
		'link' => ['dashboard','id'=>$model->id],
		'footerOptions' => [
			'class' => 'dashboard-hide',
		],
		'footer' => '<i class="fa fa-arrow-circle-left"></i> Back',
	]);
	?>
	<h3>Class</h3>
	<p>Class of Training</p>
	<?php
	Box::end();
	?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],		
			[
				'attribute' => 'class',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],

			[
				'format' => 'raw',
				'label' => 'Attendance',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data) {
					return Html::a('<i class="fa fa-fw fa-tasks"></i>', Url::to(['attendance', 'training_class_id' => $data->id]), [
							'class' => 'btn btn-default btn-xs',
							'data-pjax' => '0'
						]);
				}
			],
			
			[
				'format' => 'raw',
				'label' => 'Subject',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data)
				{
					$classSubjectCount = \backend\models\TrainingClassSubject::find()
						->where([
							'training_class_id' => $data->id
						])
						->count();
					$subjectCount = \backend\models\ProgramSubject::find()
						->where([
							'program_id' => $data->training->program_id,
							/* DIPIKIRIN NANTI.. PUYENG AKU 'revision'=> $data->training->tb_program_revision, */
							'status'=>1,
						])
						->count();
					
					if($subjectCount>$classSubjectCount){
						return Html::a($classSubjectCount.' <i class="fa fa-fw fa-plus-circle"></i>', 
								[
									'create-class-subject',
									'id'=>$data->training_id,
									'class_id'=>$data->id,
								], 
								[
									'title'=>$subjectCount,
									'class' => 'label label-info',
									'data-pjax'=>0,
									'data-toggle'=>'tooltip',
									'data-confirm'=>'Mata pelajaran akan digenerate, menyesuaikan dengan program diklatnya!'
								]);
					}
					else{
						return Html::a($classSubjectCount, 
								[
									'class-subject',
									'id'=>$data->training_id,
									'class_id'=>$data->id,
								], 
								[
									'title'=>$subjectCount,
									'class' => 'label label-info',
									'data-pjax'=>0,
									'data-toggle'=>'tooltip',
								]);
					}
				}
			],

			[
				'format' => 'raw',
				'label' => 'Schedule',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model){
					return Html::a('SET',
						Url::to(['schedule','training_class_id'=>$model->id]),
						['class'=>'label label-default', 'data-pjax' => '0']);
				}
			],

			[
				'format' => 'raw',
				'label' => 'Student',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data)
				{
					$studentCount = \backend\models\TrainingStudent::find()
						->where([
							'training_id' => $data->training_id
						])
						->count();
						
					$classStudentCount = \backend\models\TrainingClassStudent::find()
						->where([
							'training_id' => $data->training_id,
							'training_class_id' => $data->id
						])
						->count();							
					
					if($studentCount>0){
						return Html::a($classStudentCount, 
							[
								'class-student',
								'id'=>$data->training_id,
								'class_id'=>$data->id,
							], 
							[
								'title'=>$classStudentCount,
								'class' => 'label label-info',
								'data-pjax'=>'0',
								'data-toggle'=>'tooltip',
							]);
					}
					else{
						return Html::a(' <i class="fa fa-fw fa-plus-circle"></i>', 
								[
									'student',
									'id'=>$data->training_id,
								], 
								[
									'title'=>'Belum ada peserta yang didaftarkan pada diklat ini',
									'class' => 'label label-info',
									'data-pjax'=>0,
									'data-toggle'=>'tooltip',
									'data-confirm'=>'Process!'
								]);
					}
					
					/* $subjectCount = \backend\models\ProgramSubject::find()
						->where([
							'program_id' => $data->training->program_id,
							DIPIKIRIN NANTI.. PUYENG AKU 'revision'=> $data->training->tb_program_revision, 
							'status'=>1,
						])
						->count();
					
					if($subjectCount>$classSubjectCount){
						return Html::a($classSubjectCount.' <i class="fa fa-fw fa-plus-circle"></i>', 
								[
									'create-class-subject',
									'id'=>$data->training_id,
									'class_id'=>$data->id,
								], 
								[
									'title'=>$subjectCount,
									'class' => 'label label-info',
									'data-pjax'=>0,
									'data-toggle'=>'tooltip',
									'data-confirm'=>'Mata pelajaran akan digenerate, menyesuaikan dengan program diklatnya!'
								]);
					}
					else{
						return Html::a($classSubjectCount, 
								[
									'class-subject',
									'id'=>$data->training_id,
									'class_id'=>$data->id,
								], 
								[
									'title'=>$subjectCount,
									'class' => 'label label-info',
									'data-pjax'=>0,
									'data-toggle'=>'tooltip',
								]);
					} */
				}
			],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{delete}',
				'buttons' => [
					'delete' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-trash"></span>';
								return Html::a($icon,
									['delete-class','id'=>$model->training_id,'class_id'=>$model->id],
									[
										'class'=>'btn btn-default btn-xs',
										'data-pjax'=>'0',
										'data-confirm'=>'Areyou sure you want delete this item!',
										'data-method'=>'post',
									]
								);
							},
					/* 'dashboard' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-dashboard"></span>';
								return ($model->status!=2 AND $model->status!=1)?'':Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs',
									'data-pjax'=>'0',
								]);
							},
					'update' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-pencil"></span>';
								return ($model->status!=2 AND $model->status!=1)?'':Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs',
									'data-pjax'=>'0',
								]);
							}, */
				],	
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> Create ', ['create-class','id'=>$model->id], ['class' => 'btn btn-success']),
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
				'id'=>'myform',
				'onsubmit'=>'
					
				',
			],
			'action'=>[
				'class','id'=>$model->id
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
					'multiple' => true,
					'id'=>'baseon',
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
				var count = parseInt($('#count').val());
				var stock = parseInt($('#stock').val());
				var baseon = $('#baseon').val();
				if(stock<=0 || isNaN(stock)){
					alert('Tidak ada stock peserta!');
					return false;
				}
				else if(count<=0 || isNaN(count)){
					alert('Jumlah permintaan peserta tidak boleh nol!');
					$('#count').select();
					return false;
				}
				else if(stock<count){
					alert('Jumlah permintaan tidak boleh melebihi stock peserta!'+x+y);
					$('#count').select();
					return false;
				}			
				else if(baseon==null){
					alert('Dasar pengacakan harus ditentukan!');
					$('#baseon').select();	
					return false;					
				}	
			});
		");
		?>
	</div>
</div>