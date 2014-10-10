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
use yii\helpers\Inflector;
use yii\helpers\Url;

/* @var $searchModel backend\models\RoomSearch */

$this->title = 'Schedule Class #'. $class->class;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Inflector::camel2words($activity->name), 'url' => ['class','id'=>$activity->id]];
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="schedule-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	
	<div class="panel panel-default" id="booking-schedule">
	<div class="panel-heading">
		 <h3 class="panel-title"><i class="fa fa-fw fa-plus"></i> Add Activity</h3>
	</div>
	<div class="kv-panel-before">
	
	<?php 
	$url = Url::to(['activity-lists','id'=>$activity->id,'class_id'=>$class->id]);
	$form = ActiveForm::begin([
		'action' => [
			'add-activity-class-schedule',
			'id'=>$activity->id,
			'class_id'=>$class->id
		],
		'enableAjaxValidation' => false,
		'enableClientValidation' => false,
		'options'=>[
			'onsubmit'=>"
				var form = $(this);
				form.find('button[type=submit]').attr('disabled',true);
				$.ajax({
					url: $(this).attr('action'),
					type: 'post',
					data: $(this).serialize(),
					success: function(data) {
						var datas = data.split('|');						
						if(datas[1]==1){
							//SUCCESS
							$.pjax.reload({
								url: '".Url::to(['class-schedule',
									'id'=>$activity->id,'class_id'=>$class->id])."&start='+datas[3],
								container: '#pjax-gridview-schedule', 
								timeout: 3000,
							});				
							
							$('#trainingscheduleextsearch-starttime').val(datas[4]);
							$('#trainingscheduleextsearch-starttime-disp').val(datas[4]);
						}
						else{							
							alert(datas[2]);
						}
						form.find('button[type=submit]').removeAttr('disabled');
					},
					error:  function( jqXHR, textStatus, errorThrown ) {
						alert(jqXHR.responseText);
						form.find('button[type=submit]').removeAttr('disabled');
					}
				});	
				return false;
			",
		],
	]); 
	?>
	<table class="table table-striped table-condensed table-hover">
	<tr>
		<th style="width:280px;">Start Time</th>
		<th>Activity</th>
		<th style="width:100px;">Hours</th>
		<th style="width:100px;">Action</th>
	</tr>
	<tr>
		<td>
		<?php		
		echo "<div class='clearfix' style='width:275px;'>";
		echo "<div style='width:150px;' class='pull-left'>";
		echo $form->field($trainingScheduleExtSearch, 'startDate')->widget(DateControl::classname(), [
			'type'=>DateControl::FORMAT_DATE,
			'options'=>[  // this will now become the widget options for DatePicker
				'pluginOptions'=>[
					'autoclose'=>true,
					'startDate'=>date('d-m-Y',strtotime($activity->start)),
					'endDate'=>date('d-m-Y',strtotime($activity->end)),
				],// datepicker plugin options
				'convertFormat'=>true, // autoconvert PHP date to JS date format,				
			]
		])->label(false);
		echo "</div>";
		echo "<div style='width:100px;' class='pull-right'>";
		echo $form->field($trainingScheduleExtSearch, 'startTime')->widget(DateControl::classname(), [
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
		</td>
		<td>
		<?php \yii\widgets\Pjax::begin([
			'id'=>'pjax-select-activity',
		]); ?>
		<?php
		$trainingClassSubject = \backend\models\TrainingClassSubject::find()
			->where(['training_class_id'=>$class->id,'status'=>1])
			->all();
		$data=[];
		foreach($trainingClassSubject as $tcs){
			// History Subject Ntar Dulu
			$program_subject_id = $tcs->program_subject_id;
			$programSubject = \backend\models\ProgramSubject::find()
			->where([
				'id'=>$program_subject_id,
				'program_id'=>$class->training->program_id,
				'status'=>1
			])
			->one();
			if(null!=$programSubject){
				$ts =  \backend\models\TrainingSchedule::find()
					->select(['used_hours'=>'sum(hours)'])
					->where(['training_class_subject_id'=>$tcs->id,'status'=>1])
					->groupBy('training_class_subject_id')
					->asArray()
					->one();
				$available_hours = $programSubject->hours - $ts['used_hours'];
				if($available_hours>0){
					$name = $programSubject->name.' '.$available_hours.' JP';
					$data[$tcs->id]=$name;
				}
				else{
					
				}
			}
		}
		
		$data[-1] = 'Coffe Break';
		$data[-2] = 'Ishoma';
		$data[-3] = 'Others';
		
		
		// The controller action that will render the list
		$url = Url::to(['activity-lists','id'=>$activity->id,'class_id'=>$class->id]);
		 
		// Script to initialize the selection based on the value of the select2 element
		$initScript = "
			function (element, callback) {
				var id=$(element).val();
				if (id !== '') {
					$.ajax('".$url."', {
						dataType: 'json'
					}).done(function(data) { 
						callback(data.results);
					});
				}
			}
		";
		//$this->registerJs('$.ajax("'.$url.'", {dataType: "json"}).done(function(data) { alert(data.results.text); callback(data.results);});');
		echo $form->field($trainingScheduleExtSearch, 'training_class_subject_id')->widget(Select2::classname(), [
			'data' => $data,
			'options' => [
				'placeholder' => 'Choose Training Class Subject ...',
				'onchange'=>'
					$("#trainingscheduleextsearch-activity").prop("disabled",false);
					$("#trainingscheduleextsearch-pic").prop("disabled",false);
					$("#trainingscheduleextsearch-minutes").prop("disabled",false)
					$("#trainingscheduleextsearch-hours").prop("disabled",true);
					if($(this).val()==-1){
						$("#trainingscheduleextsearch-activity").val("Coffe Break");
						$("#trainingscheduleextsearch-pic").val("-");	
						$("#trainingscheduleextsearch-minutes").val("15");	
						$("#trainingscheduleextsearch-minutes").select();
					} 
					else if ($(this).val()==-2){
						$("#trainingscheduleextsearch-activity").val("Ishoma");
						$("#trainingscheduleextsearch-pic").val("-");		
						$("#trainingscheduleextsearch-minutes").val("60");		
						$("#trainingscheduleextsearch-minutes").select();
					}
					else if ($(this).val()==-3){
						$("#trainingscheduleextsearch-activity").val("Nama Kegiatan??");
						$("#trainingscheduleextsearch-pic").val("PIC Kegiatan");
						$("#trainingscheduleextsearch-minutes").val("30");	
						$("#trainingscheduleextsearch-activity").select();												
					}
					else{
						//$("#other-activity").slideUp("slow");
						// DISABLED
						$("#trainingscheduleextsearch-activity").val("");
						$("#trainingscheduleextsearch-pic").val("");
						$("#trainingscheduleextsearch-activity").prop("disabled",true);
						$("#trainingscheduleextsearch-pic").prop("disabled",true);
						$("#trainingscheduleextsearch-minutes").prop("disabled",true);
						
						// ENABLED
						$("#trainingscheduleextsearch-hours").prop("disabled",false);
						$("#trainingscheduleextsearch-hours").select();
					}
				',
			],
			'pluginOptions' => [
				'allowClear' => true,
			],
		])->label(false);  ?>
		<?php 
		if(isset($_GET['s2I'])){
			$this->registerJs('
				$("#trainingscheduleextsearch-training_class_subject_id").select2().select2("val", '.$_GET['s2I'].');
			');
		}
		?>
		
		<?php \yii\widgets\Pjax::end(); ?>
		</td>
		<td>
		<?php echo $form->field($trainingScheduleExtSearch, 'hours')->textInput(['placeholder' => 'In JP',])->label(false)  ?>
		</td>
		<td rowspan="2">
			<?= Html::submitButton(
			'<span class="fa fa-fw fa-plus"></span>', 
			['class' => 'btn btn-primary']) ?>
		</td>
	</tr>
	<tr>
		<td>
		<?php
		$activityRoom = \backend\models\ActivityRoom::find()
			->where([
				'activity_id'=>$class->training_id,
				'status'=>2
			])
			->all();
		$dataRoom=[];	
		foreach ($activityRoom as $ar){
			$dataRoom[$ar->room_id] = $ar->room->name;
			if(empty($firstAR)){
				$trainingScheduleExtSearch->activity_room_id = $ar->room_id;
			}
		}
		echo $form->field($trainingScheduleExtSearch, 'activity_room_id')->widget(Select2::classname(), [
			'data' => $dataRoom,
			'options' => [
				'placeholder' => 'Choose Activity Room ...',
				'onchange'=>'
				',
			],
			'pluginOptions' => [
				'allowClear' => true,
			],
		])->label(false); 
		$this->registerCss('#s2id_trainingscheduleextsearch-activity_room_id { width: 275px !important; }');
		?>
		</td>
		<td>
		<div id="other-activity">
			<?php echo $form->field($trainingScheduleExtSearch, 'activity')->textInput(['placeholder' => 'Other Activity',])->label(false) ?>			
			<?php echo $form->field($trainingScheduleExtSearch, 'pic')->textInput(['placeholder' => 'PIC Activity',])->label(false) ?>
		</div>
		</td>
		<td>
			<?php echo $form->field($trainingScheduleExtSearch, 'minutes')->textInput(['placeholder' => 'In Minute',])->label(false) ?>
		</td>
	</table>
	<?php ActiveForm::end(); ?>
	</div>
	</div>
	
	
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview-schedule',
	]); ?>
	<?php 
	if (Yii::$app->request->isAjax){	
		echo AlertBlock::widget([
			'useSessionFlash' => true,
			'type' => AlertBlock::TYPE_ALERT
		]); 
	}
	?>	
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'label' => 'Datetime',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'200px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($data){
					$start = date('d-M-Y H:i',strtotime($data->start));
					$finish = date('d-M-Y H:i',strtotime($data->end));
					$startDate = date('d-M-Y',strtotime($data->start));
					$finishDate = date('d-M-Y',strtotime($data->end));
					$startTime = date('H:i',strtotime($data->start));
					$finishTime = date('H:i',strtotime($data->end));
					
					if($start==$finish){
						return $start;
					}
					else if($startDate==$finishDate){
						return '<span class="label label-info">'.$startDate .'</span> <span class="label label-default">' .$startTime. ' s.d ' . $finishTime.'</span>';
					}
					else{
						return '<span class="label label-info">'.$start .'</span>&nbsp;<span class="label label-default"> s.d </span>&nbsp;<span class="label label-info">'.$finish.'</span>';
					}
				}
			],
			[
				'label' => 'Activity',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($data) use ($activity) {
					if($data->training_class_subject_id>0){
						$trainingClassSubject = \backend\models\TrainingClassSubject::findOne($data->training_class_subject_id);
						if($trainingClassSubject!=null){
							$programSubject = \backend\models\ProgramSubject::find()
							->where([
								'id'=>$trainingClassSubject->program_subject_id,
								'status'=>1
							])
							->one();
							return $programSubject->name;
							/* $program_subject_id = $trainingClassSubject->program_subject_id;
							$program_id = $activity->program_id;
							$program_revision =  $activity->program_revision;
							$programSubjectHistory = \backend\models\ProgramSubjectHistory::find()
							->where([
								'tb_program_subject_id'=>$tb_program_subject_id,
								'tb_program_id'=>$tb_program_id,
								'revision'=>$tb_program_revision,
								'status'=>1
							])
							->one();
							if(null!=$programSubjectHistory){
								$name = $programSubjectHistory->subjectType->name.' '.$programSubjectHistory->name;
								return $name;
							}
							else{
								return "Undefined??? hello??";
							} */
							
						}
						else{
							return "Undefined??? hello??";
						}
					}
					else{
						return $data->activity;
					}
				}
			],
			[
				'label' => 'Hours',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'50px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($data){
					if($data->training_class_subject_id>0){
						return $data->hours;
					}
					else{
						return '';
					}
				}
			],
			[
				'label' => 'PIC/Narasumber',
				'vAlign'=>'middle',
				'width'=>'200px;',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($data)use ($activity, $class){
					if($data->training_class_subject_id>0){
						// FIND PENGAJAR pada tb_training_schedule_trainer (id, tb_training_schedule_id, tb_trainer_id, status);
						$content = Html::a('<i class="fa fa-plus"></i> Add',
						[
						'trainer-class-schedule',
						'id'=>$activity->id,
						'class_id'=>$class->id,
						'schedule_id'=>$data->id,
						],
						[
							'class' => 'label label-success modal-heart',
							'data-pjax'=>0,
							'title'=>'Click to add trainer!',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
							'modal-size' => 'modal-lg',
						]);
						
						$trainingScheduleTrainer = \backend\models\TrainingScheduleTrainer::find()
							->where([
								'training_schedule_id'=>$data->id,
								'status'=>1,
							])
							->orderBy('type ASC')
							->all();
						$type= "-1";	
						$idx = 1;
						foreach($trainingScheduleTrainer as $trainer){
							if($type!=$trainer->type){
								$content .="<hr style='margin:2px 0'>";
								/* $content .="<strong>".$trainer->trainerType->name."</strong>"; */
								$content .="<hr style='margin:2px 0'>";
								$type=$trainer->type;
								$idx=1;
							}
							
							$content .="<div>";
							$content .="<span  class='label label-default' data-toggle='tooltip' title='".$trainer->trainer->person->organisation." - ".$trainer->trainer->person->phone."'>".$idx++.". ".$trainer->trainer->person->name."</span> ";
							$content .=Html::a('<span class="glyphicon glyphicon-trash"></span>', 
							[
							'delete-trainer',
							'id'=>$data->id,
							'tb_trainer_id'=>$trainer->trainer_id,
							], 
							[
							'class' => 'label label-danger link-post',
							'data-pjax'=>0,
							'title'=>'click to delete it!',
							//'data-confirm'=>'Are sure delete it!',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
							]);	
							$content .="</div>";							
						}
					}
					else{
						$content = $data->pic;
					}
					
					
					return $content;
				}
			],
			[
				'label' => 'Room',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'10px;',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($data) use ($activity, $class){
					if($data->activity_room_id>0){
						$ar = \backend\models\ActivityRoom::find()
							->where([
								'activity_id'=>$activity->id,
								'room_id' => $data->activity_room_id,
							])
							->one();
						$room = $ar->room->name;
						$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
						if($ar->room->satker_id!=$satker_id){
							$room .= ' ['.$ar->room->satker->name.'] ';
						} 
						return Html::a($ar->room_id,
							[
							'room-class-schedule',
							'id'=>$activity->id,
							'class_id'=>$class->id,
							'schedule_id'=>$data->id,
							],
							[
							'class' => 'label label-warning modal-heart',
							'data-pjax'=>0,
							'title'=>$room,
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
							'modal-title'=>'Set Room',
						]);
					}
					else{
						return Html::a('-',
						[
						'room-class-schedule',
						'id'=>$activity->id,
						'class_id'=>$class->id,
						'schedule_id'=>$data->id,
						],
						[
							'class' => 'label label-warning modal-heart',
							'data-pjax'=>0,
							'title'=>'Click to set room!',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
						]);
					}
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
				'value' => function ($data) use($activity, $class){
					$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
					$delete = false;
					if($data->trainingClass->training->activity->satker_id==$satker_id){
						$delete=true;
					}
					
					if($delete){
						return Html::a('<span class="fa fa-times"></span>', 
							[
							'delete-activity-class-schedule',
							'id'=>$activity->id,
							'class_id'=>$class->id,
							'schedule_id'=>$data->id,
							], 
							[
							'class' => 'label label-danger link-post',
							'data-pjax'=>0,
							'title'=>'click to delete it!',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
							]);
					}
				}
			],
			
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Schedule</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['class','id'=>$activity->id], ['class' => 'btn btn-warning']).' '.
				Html::a('<i class="fa fa-fw fa-plus"></i> Add Activity', '#', ['class' => 'btn btn-success','onclick'=>"$('#booking-schedule').slideToggle('slow');return false;",'pjax'=>0]).' '.
				'<div class="pull-right" style="margin-right:5px; width:150px;">'.
				$form->field($trainingScheduleExtSearch, 'scheduleDate')->widget(DateControl::classname(), [
					'type'=>DateControl::FORMAT_DATE,
					'options'=>[  // this will now become the widget options for DatePicker
						'pluginOptions'=>[
							'autoclose'=>true,
							'startDate'=>date('d-m-Y',strtotime($activity->start)),
							'endDate'=>date('d-m-Y',strtotime($activity->end)),
							
						],
						'pluginEvents' => [
							//"show" => "function(e) {  # `e` here contains the extra attributes }",
							//"hide" => "function(e) {  # `e` here contains the extra attributes }",
							//"clearDate" => "function(e) {  # `e` here contains the extra attributes }",
							"changeDate" => "function(e) { 
								date = new Date(e.date);
								year = date.getFullYear(); 
								month = date.getMonth()+1; 
								day = date.getDate(); 
								var start = year+'-'+month+'-'+day;
								$.pjax.reload({
									url: '".\yii\helpers\Url::to(['class-schedule','id'=>$activity->id,'class_id'=>$class->id])."&start='+start,
									container: '#pjax-gridview-schedule', 
									timeout: 3000,
								});	
								
								var startF = $('#trainingscheduleextsearch-scheduledate-disp').val()
								$('#trainingscheduleextsearch-startdate').val(start)
								$('#trainingscheduleextsearch-startdate-disp').val(startF)
								$('#trainingscheduleextsearch-starttime-disp').val('08:00')
								
							}",
							//"changeYear" => "function(e) {  # `e` here contains the extra attributes }",
							//"changeMonth" => "function(e) {  # `e` here contains the extra attributes }",
						],
						// datepicker plugin options
						'convertFormat'=>true, // autoconvert PHP date to JS date format,
						
					]
				])->label(false).
				'</div>',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', ['class-schedule','id'=>$activity->id,'class_id'=>$class->id], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?php
	$this->registerJs('
			if($("#trainingscheduleextsearch-training_class_subject_id").val()>0){
				var select2Index = $("#trainingscheduleextsearch-training_class_subject_id").val();
				$.pjax.reload({
					url: "'.\yii\helpers\Url::to(['class-schedule',
						'id'=>$activity->id,
						'class_id'=>$class->id,
						'start'=>$start,
					]).'&s2I="+select2Index,
					container: "#pjax-select-activity", 
					timeout: 3000,
				});
				$("#trainingscheduleextsearch-hours").val(0)
			}
		
			$( "a.link-post" ).click(function() {	
				if(!confirm("Are you sure delete it??")) return false;	
				var link = $(this)
				$.ajax({
					url: link.attr("href"),
					type: "post",
					//data: $("#form-available-room").serialize(),
					success: function(data) {
						var datas = data.split("|");	
						if(datas[1]==0){
							alert(datas[2]);
						}
						else{
							$("#trainingscheduleextsearch-starttime").val(datas[4]);
							$("#trainingscheduleextsearch-starttime-disp").val(datas[4]);
							$.pjax.reload({
								url: "'.Url::to(['class-schedule','id'=>$activity->id,'class_id'=>$class->id]).'&start="+datas[3],
								container: "#pjax-gridview-schedule", 
								timeout: 3000,
							});					
						}
					},
					error:  function( jqXHR, textStatus, errorThrown ) {
						alert(jqXHR.responseText);
					}
				});	
				return false;
			});
			
			$.ajax({
				url: "'.Url::to(['class-schedule-max-time','id'=>$activity->id,'class_id'=>$class->id,'start'=>$start]).'",
				type: "post",
				//data: $("#form-available-room").serialize(),
				success: function(data) {
					//var datas = data.split("|");	
					$("#trainingscheduleextsearch-starttime").val(data);
					$("#trainingscheduleextsearch-starttime-disp").val(data);
				},
				error:  function( jqXHR, textStatus, errorThrown ) {
					alert(jqXHR.responseText);
				}
			});	
			
			$(".modal-heart").on("click", function () {
				var $modal = $("#modal-heart");
				var $link = $(this);
				var $source = $link.attr("source")
				$modal.find(".modal-refresh").attr("href", $link.attr("href"));
				if ($link.attr("title")) {
					$modal.find(".modal-title").text($link.attr("title"));
				}
				else {
					$modal.find(".modal-title").html($link.attr("modal-title")); // warning: klo attribut title dan modal-title ada 2-2 nya
																				 // yang menang bakal yang title.
				}
				$modal.find(".modal-body .content").html("Loading ...");
				$modal.modal("show");
				
				$.ajax({
					type: "POST",
					cache: false,
					url: $link.prop("href"),
					data: $(".form-heart form").serializeArray(),
					success: function (data) {		
						if ($source) 
							result = $(data).find($source);
						else
							result = data;
						$modal.find(".modal-body .content").html(result);
						$modal.find(".modal-body .content").css("max-height", ($(window).height() - 200) + "px");
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						$modal.find(".modal-body .content").html("<div class=\"error\">" + XMLHttpRequest.responseText + "</div>");
					}
				});
				return false;
			});
			
			$("[data-toggle=\'tooltip\']").tooltip();

	');
	?>
	<?php \yii\widgets\Pjax::end(); ?>
	
	<?php 
	$this->registerJs('
		$("#booking-schedule").slideToggle("slow");
		$("#trainingscheduleextsearch-activity").prop("disabled",true);
		$("#trainingscheduleextsearch-pic").prop("disabled",true);
		$("#trainingscheduleextsearch-minutes").prop("disabled",true);
		$("#trainingscheduleextsearch-hours").prop("disabled",false);
	'); 			
	?>
	<?= \hscstudio\heart\widgets\Modal::widget(['registerAsset'=>true]) ?>
</div>
