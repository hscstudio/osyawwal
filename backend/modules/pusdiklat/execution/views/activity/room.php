<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Dropdown;
use kartik\widgets\Select2;
use kartik\widgets\AlertBlock;
use kartik\widgets\ActiveForm;
use \kartik\widgets\DatePicker;
use \kartik\widgets\TimePicker;
use \kartik\datecontrol\DateControl;
use kartik\checkbox\CheckboxX;
use hscstudio\heart\widgets\Box;
/* @var $searchModel backend\models\RoomSearch */

$this->title = \yii\helpers\Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => 'Training', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="room-index">
	<?php
		Box::begin([
			'type'=>'small', // ,small, solid, tiles
			'bgColor'=>'aqua', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
			'bodyOptions' => [],
			'icon' => 'fa fa-fw fa-home',
			'link' => ['dashboard','id'=>$model->id],
			'footerOptions' => [
				'class' => 'dashboard-hide',
			],
			'footer' => '<i class="fa fa-arrow-circle-left"></i> Back',
		]);
		?>
		<h3>Room</h3>
		<p>Room of Training</p>
	<?php
	Box::end();
	?>	

	<div class="panel panel-default" id="booking-room">
	<div class="panel-heading">
		 <h3 class="panel-title"><i class="fa fa-fw fa-search"></i> Find Available Room</h3>
	</div>
	<div class="kv-panel-before">
	<div class="row">
		<div class="col-md-4">
		<?php 
		$form = ActiveForm::begin([
			'action' => ['available-room','id'=>$model->id],
			'enableAjaxValidation' => false,
			'enableClientValidation' => true,
			'options'=>[
				'id'=>'form-available-room',
				'onsubmit'=>"
					$.ajax({
						url: $(this).attr('action'),
						type: 'post',
						data: $(this).serialize(),
						success: function(data) {
							$('#available-room').html(data);
						},
						error:  function( jqXHR, textStatus, errorThrown ) {
							$('#available-room').html(jqXHR.responseText);
						}
					});	

					return false;
				",
			],
		]); 
		?>
		<?php
		echo '<label class="control-label">Start Time</label>';		
		echo "<div class='clearfix' style='width:275px;'>";
		echo "<div style='width:150px;' class='pull-left'>";
		echo $form->field($searchActivityRoomModel, 'startDateX')->widget(DateControl::classname(), [
			'type'=>DateControl::FORMAT_DATE,
			'options'=>[  // this will now become the widget options for DatePicker
				'pluginOptions'=>[
					'autoclose'=>true,
					'startDate'=>date('d-m-Y',strtotime($model->start)),
					'endDate'=>date('d-m-Y',strtotime($model->end)),
				],// datepicker plugin options
				'convertFormat'=>true, // autoconvert PHP date to JS date format,
				
			]
		])->label(false);
		echo "</div>";
		echo "<div style='width:100px;' class='pull-right'>";
		echo $form->field($searchActivityRoomModel, 'startTimeX')->widget(DateControl::classname(), [
			'type'=>DateControl::FORMAT_TIME,
			'options'=>[  // this will now become the widget options for DatePicker
				'pluginOptions'=>[
					'autoclose'=>true,
					'showMeridian' => false,
					'minuteStep' => 1,
					'defaultTime'=> '08:00',
				],// datepicker plugin options
				'convertFormat'=>true, // autoconvert PHP date to JS date format,
			]
		])->label(false);
		echo "</div>";
		echo "</div>";
		?>
		
		<?php
		echo '<label class="control-label">End Time</label>';		
		echo "<div class='clearfix' style='width:275px;'>";
		echo "<div style='width:150px;' class='pull-left'>";
		echo $form->field($searchActivityRoomModel, 'endDateX')->widget(DateControl::classname(), [
			'type'=>DateControl::FORMAT_DATE,
			'options'=>[  // this will now become the widget options for DatePicker
				'pluginOptions'=>[
					'autoclose'=>true,
					'startDate'=>date('d-m-Y',strtotime($model->start)),
					'endDate'=>date('d-m-Y',strtotime($model->end)),
				],// datepicker plugin options
				'convertFormat'=>true, // autoconvert PHP date to JS date format,
				
			]
		])->label(false);
		echo "</div>";
		echo "<div style='width:100px;' class='pull-right'>";
		echo $form->field($searchActivityRoomModel, 'endTimeX')->widget(DateControl::classname(), [
			'type'=>DateControl::FORMAT_TIME,
			'options'=>[  // this will now become the widget options for DatePicker
				'pluginOptions'=>[
					'autoclose'=>true,
					'showMeridian' => false,
					'minuteStep' => 1,
					'defaultTime'=> '17:00',
				],// datepicker plugin options
				'convertFormat'=>true, // autoconvert PHP date to JS date format,
			]
		])->label(false);
		echo "</div>";
		echo "</div>";
		?>
		
		<div class="row clearfix">
		<div class="col-md-6">
		<?= $form->field($searchActivityRoomModel, 'computer')->widget(CheckboxX::classname(), [
			'pluginOptions'=>['threeState'=>false,'size'=>'sm','inline'=>true, ],
			'options'=>[
				//'value'=>1
			],
		]); ?>
		</div>
		<div class="col-md-6">
		<?= $form->field($searchActivityRoomModel, 'hostel')->widget(CheckboxX::classname(), [
			'pluginOptions'=>['threeState'=>false,'size'=>'sm','inline'=>true, ]
		]); ?>
		</div>
		</div>
		
		<div class="row clearfix">
			<div class="col-md-4">
			<?= $form->field($searchActivityRoomModel, 'capacity')->textInput(['maxlength' => 5]) ?>
			</div>
		</div>
		
		<div class="row clearfix">
			<div class="col-md-10">
			<?php
			echo $form->field($searchActivityRoomModel, 'location')->widget(Select2::classname(), [
				'data' => $satkers,
				'options' => [
					'placeholder' => 'Choose Satker ...'
				],
				'pluginOptions' => [
					'allowClear' => true
				],
			]); 
			
			?>
			</div>
		</div>
		
		<?= Html::submitButton(
			'<span class="fa fa-fw fa-search"></span> Search', 
			['class' => 'btn btn-primary']) ?>
		</div>			
		<div class="col-md-8" id="available-room">
		
		</div>			
	</div>
	</div>
	</div>
	<div class="clearfix"></div>
	<?php ActiveForm::end(); ?>
	
	<?php 
	/* $this->registerCss('.select2-container { width: 200px !important; }'); */
	$this->registerJs('
			$("#form-available-room").submit();
	');
	?>
	
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview-room',
	]); ?>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'label' => 'Waktu',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'250px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($model){
					$start = date('d-M-Y H:i',strtotime($model->start));
					$end = date('d-M-Y H:i',strtotime($model->end));
					$startDate = date('d-M-Y',strtotime($model->start));
					$endDate = date('d-M-Y',strtotime($model->end));
					$startTime = date('H:i',strtotime($model->start));
					$endTime = date('H:i',strtotime($model->end));
					
					if($start==$end){
						return '<span class="label label-info">'.$start.'</span>';
					}
					else if($startDate==$endDate){
						return '<span class="label label-info">'.$startDate .'</span> <span class="label label-default">' .$startTime. ' s.d ' . $endTime.'</span>';
					}
					else{
						return '<span class="label label-info">'.$start .'</span>&nbsp;<span class="label label-default"> s.d </span>&nbsp;<span class="label label-info">'.$end.'</span>';
					}
				}
			],
			[
				'header' => '<div class="kv-sticky-column kv-align-center kv-align-middle">Room</div>',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($data){
					$room = $data->room->name;
					$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
					if($data->room->satker_id!=$satker_id){
						$room .= '<br><span class="badge">'.$data->room->satker->name.'</span>';
					} 
					return $room;
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
				'format' => 'raw',
				'label' => 'Action',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data) use ($model){
					$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
					$delete = false;
					if($data->room->satker_id==$satker_id){
						if($data->status==1) $delete=true;
					}
					else{
						if($data->status==0) $delete=true;
					}
					
					if($delete){
						return Html::a('<span class="fa fa-times"></span>', 
							[
							'unset-room',
							'id'=>$model->id,
							'room_id'=>$data->room->id,
							], 
							[
							'class' => 'label label-danger link-post-2','data-pjax'=>0,
							'title'=>'click to unset it!',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
							'data-method' => "post",
							]);
					}
				}
			],
			
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> List of Booked Room</h3>',
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> Booking Room', '#', ['class' => 'btn btn-success','onclick'=>"$('#booking-room').slideToggle('slow');return false;",'pjax'=>0]),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', ['room','id'=>$model->id], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	
	<?php \yii\widgets\Pjax::end(); ?>	
	

</div>
