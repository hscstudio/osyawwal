<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Dropdown;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper; 
use kartik\widgets\DatePicker;

/* @var $searchModel backend\models\TrainingClassStudentSearch */

$this->title = 'Certificate : '.\yii\helpers\Inflector::camel2words($training->name);
$this->params['breadcrumbs'][] = ['label' => 'Trainings', 'url' => \yii\helpers\Url::to(['/'.$this->context->module->uniqueId.'/training2/index'])];
$this->params['breadcrumbs'][] = ['label' => 'Training Class', 'url' => \yii\helpers\Url::to(['/'.$this->context->module->uniqueId.'/training-class2/index','tb_training_id'=>$tb_training_id])];
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="training-class-student-index">

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
				'attribute' => 'tb_student_id',
				'label' => 'Name',
				'value' => function ($data) {
					return $data->trainingStudent->student->name;
				}
			],
			[
				'label' => 'NIP',
				'width' => '200px',
				'value' => function ($data) {
					return $data->trainingStudent->student->nip;
				}
			],
			[
				'label' => 'Satker',
				'format' => 'raw',
				'width' => '200px',
				'value' => function ($model) {
					$eselon = $model->trainingStudent->student->satker;
					$satker = [
						'1'=>$model->trainingStudent->student->unit->shortname.' ',
						'2'=>$model->trainingStudent->student->eselon2.' ',
						'3'=>$model->trainingStudent->student->eselon3.' ',
						'4'=>$model->trainingStudent->student->eselon4.' ',
					];			
					
					$icon=$satker[$eselon];
					$label='label label-success';
					$title=($eselon==1)?'':'Eselon '.($eselon-1).': '.$satker[$eselon-1];					
					
					return Html::tag('span', $icon, ['class'=>$label,'title'=>$title,'data-toggle'=>"tooltip",'data-placement'=>"top",'style'=>'cursor:pointer']);
				}
			],         
			[
				'label' => 'Check',
				'format'=>'raw',
				'vAlign'=>'middle',
				'width' => '25px',
				'value' => function ($model) {
					$icon='<span class="glyphicon glyphicon-check"></span>';
					$err = 0;
					$student = $model->trainingStudent->student;
					if (!(empty($student->name))){
						$check[] = '<span class="glyphicon glyphicon-check"></span>'.' name';
					}
					else{
						$check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' name is empty';
						$err++;
					}
					
					if (!(empty($student->nip))){
						$check[] = '<span class="glyphicon glyphicon-check"></span>'.' nip';
					}
					else{
						$check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' nip is empty';
						$err++;
					}
					
					if (!(empty($student->born))){
						$check[] = '<span class="glyphicon glyphicon-check"></span>'.' born';
					}
					else{
						$check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' born is empty';
						$err++;
					}
					
					if (( date('Y') - substr($student->birthDay,0,4))>10){
						$check[] = '<span class="glyphicon glyphicon-check"></span>'.' birhtday';
					}
					else{
						$check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' birhtday is invalid';
						$err++;
					}
					
					if ($student->ref_rank_class_id==0){
						$check[] = '<span class="glyphicon glyphicon-check"></span>'.' rank class';
					}
					else{
						$check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' rank class is invalid';
						$err++;
					}
					
					$eselon = $student->satker;
					$satker = [
						'1'=>$student->unit->shortname.' ',
						'2'=>$student->eselon2.' ',
						'3'=>$student->eselon3.' ',
						'4'=>$student->eselon4.' ',
					];	
					
					if (strlen($satker[$eselon])>=3){
						$check[] = '<span class="glyphicon glyphicon-check"></span>'.' unit';
					}
					else{
						$check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' unit is invalid';
						$err++;
					}				
					
					$path = '';
					if(isset(Yii::$app->params['uploadPath'])){
						$path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
					}
					else{
						$path = Yii::getAlias('@common').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR;
					}
					if (file_exists($path.'student/'.$student->id.'/'.$student->photo) and strlen($student->photo)>3){
						$check[] = '<span class="glyphicon glyphicon-check"></span>'.' photo';
					}
					else{
						$check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' photo is invalid';
						$err++;
					}
					
					if($err>0) $icon='<span class="text-danger glyphicon glyphicon-info-sign"></span>';
					
					$title = implode('<br>',$check);
					return Html::a($icon,'#',[
								'data-pjax'=>"0",
								'data-toggle'=>"tooltip",
								'data-html'=>"true",
								'title'=>$title
							]);
					
				}
			],			
			[
				'label' => 'Certificate',
				'format' => 'raw',
				'width' => '200px',
				'value' => function ($model) {
					$certificate = backend\models\TrainingClassStudentCertificate::findOne($model->id);
					if (null!=$certificate){
						$icon='<span class="glyphicon glyphicon-check"></span>';
						$label='label label-success';
						$title='Get Certificate';
					}	
					else{ 
						$icon='-';
						$label='';
						$title='-';
					}
					
					return Html::tag('span', $icon, ['class'=>$label,'title'=>$title,'data-toggle'=>"tooltip",'data-placement'=>"top",'style'=>'cursor:pointer']);
				}
			],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template'=>'{create} {update} {view} {delete}',
				'buttons' => [
					'update' => function ($url, $model) use ($tb_training_id,$tb_training_class_id) {
								$certificate = backend\models\TrainingClassStudentCertificate::findOne($model->id);
								if (null!=$certificate){
									$icon='<span class="glyphicon glyphicon-pencil"></span>';
									$url2=['update2','id'=>$model->id,'tb_training_id'=>$tb_training_id,'tb_training_class_id'=>$tb_training_class_id];
									return Html::a($icon,$url2,[
										'data-pjax'=>"0",
									]);
								}
							},
					'delete' => function ($url, $model) use ($tb_training_id,$tb_training_class_id) {
								$certificate = backend\models\TrainingClassStudentCertificate::findOne($model->id);
								if (null!=$certificate){
									$icon='<span class="glyphicon glyphicon-trash"></span>';
									$url2=['delete2','id'=>$model->id,'tb_training_id'=>$tb_training_id,'tb_training_class_id'=>$tb_training_class_id];
									return Html::a($icon,$url2,[
										'title'=>"Delete",'data-confirm'=>"Are you sure to delete this item?",'data-method'=>"post",
										'data-pjax'=>"0",
									]);
								}
							},
					'view' => function ($url, $model)use ($tb_training_id,$tb_training_class_id) {
								$certificate = backend\models\TrainingClassStudentCertificate::findOne($model->id);
								if (null!=$certificate){
									$icon='<span class="glyphicon glyphicon-eye-open"></span>';
									$url2=['view2','id'=>$model->id,'tb_training_id'=>$tb_training_id,'tb_training_class_id'=>$tb_training_class_id];
									return Html::a($icon,$url2,[
										'data-pjax'=>"0",
									]);
								}
							},
					'create' => function ($url, $model)use ($tb_training_id,$tb_training_class_id) {
								$certificate = backend\models\TrainingClassStudentCertificate::findOne($model->id);
								if (null!=$certificate){
								}
								else{
									$icon='<span class="glyphicon glyphicon-plus"></span>';
									$url2 = ['create2','id'=>$model->id,'tb_training_id'=>$tb_training_id,'tb_training_class_id'=>$tb_training_class_id];
									return Html::a($icon,$url2,[
										'data-pjax'=>"0",
									]);
								}
							},
				],			
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i></h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back To Training Class', \yii\helpers\Url::to(['/'.$this->context->module->uniqueId.'/training-class2/index','tb_training_id'=>$tb_training_id]), ['class' => 'btn btn-warning']).' '.
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'class', 
					'data' => $listTrainingClass,
					'value' => $tb_training_class_id,
					'options' => [
						'placeholder' => 'Class ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['certificate','tb_training_id'=>$tb_training_id]).'&tb_training_class_id="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', ['certificate','tb_training_id'=>$tb_training_id,'tb_training_class_id'=>$tb_training_class_id], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	
	<div class="panel panel-default">
        <div class="panel-heading">
             <h3 class="panel-title"><i class="fa fa-fw fa-refresh"></i> Document Generator</h3>
        </div>
		
		<div class="panel-body">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
			  <li class="active"><a href="#sertifikat" role="tab" data-toggle="tab">Sertifikat</a></li>
			  <li><a href="#profile" role="tab" data-toggle="tab">Profile</a></li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content" style="border:1px solid #ddd;border-top-width:0">
			  <div class="tab-pane fade in active" id="sertifikat">
				<?= $this->render('_printCertificate', [
					'training' => $training,
					'tb_training_id'=>$tb_training_id,
					'tb_training_class_id'=>$tb_training_class_id,
				]) ?>
			  </div>
			  <div class="tab-pane fade" id="profile">...</div>
			</div>	

			
		</div>
	</div>
	
	<?php \yii\widgets\Pjax::end(); ?>
	
</div>
