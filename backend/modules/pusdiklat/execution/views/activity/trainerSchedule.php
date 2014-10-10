<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\TrainingClass */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="training-class-form">
<div class="panel panel-default">
	<div class="panel-heading">
		<strong>Set Trainer</strong>
	</div>
	<div style="margin:10px">
    <?php $form = ActiveForm::begin([
		'action' => ['trainer-class-schedule','id'=>$activity->id,'class_id'=>$class->id,'schedule_id'=>$trainingSchedule->id],
		'enableAjaxValidation' => false,
		'enableClientValidation' => false,
		'options'=>[
			'onsubmit'=>"
				$.ajax({
					url: $(this).attr('action'),
					type: 'post',
					data: $(this).serialize(),
					success: function(data) {
						var datas = data.split('|');						
						if(datas[1]==1){
							//$('#modal-heart').modal('hide');
							//SUCCESS
							alert(datas[2])
							$.pjax.reload({
								url: '".\yii\helpers\Url::to(['class-schedule','id'=>$activity->id,'class_id'=>$class->id])."&start='+datas[3],
								container: '#pjax-gridview-schedule', 
								timeout: 3000,
							});				
							
							//$('#trainingscheduleextsearch-starttime').val(datas[4])
							//$('#trainingscheduleextsearch-starttime-disp').val(datas[4])
						}
						else{
							alert(datas[2]);
						}
					},
					error:  function( jqXHR, textStatus, errorThrown ) {
						alert(jqXHR.responseText);
					}
				});	
				return false;
			",
		],
	]); ?>
	<?= $form->errorSummary($model) ?>
	
    <?php
	
	$type='-1';		
	$idx=1;
	echo "<table class='table table-condensed table-striped'>";
	foreach($trainingSubjectTrainerRecommendation as $tstr){
		if($type!=$tstr->type){
			echo "<tr>";
			echo "<th style='width:30px;'>#</th>";
			echo "<th>"."<strong>".$tstr->reference->name."</strong>"."</th>";
			echo "<th>Phone</th>";
			echo "<th>Organisation</th>";
			echo "<th style='width:50px;'>Set</th>";
			$type=$tstr->type;
			$idx=1;
		}
		echo "<tr>";
		echo "<td>";
		echo $idx++;
		echo "</td>";
		echo "<td>";
		echo $tstr->trainer->person->name;
		$startSearch = date('Y-m-d H:i',strtotime($trainingSchedule->start) + 1); // [08:00 - 09:00, 09:00 - 10:00] not excact between :)
		$endSearch = date('Y-m-d H:i',strtotime($trainingSchedule->end) - 1);
		//FIND SCHEDULE YANG BERJALAN DALAM WAKTU SAMA
		$trainingSchedule2 = \backend\models\TrainingSchedule::find()
				->where('
					((start between :start AND :end)
						OR (end between :start AND :end))
					AND
					status = 1
				',
				[
					':start' => $startSearch,
					':end' => $endSearch,
				])
				->all();
		$available = true;
		$available_info = "available to this time";
		foreach($trainingSchedule2 as $ts2){
			$trainingScheduleTrainer = \backend\models\TrainingScheduleTrainer::find()
			->where([
				'training_schedule_id' => $ts2->id,
				'trainer_id' => $tstr->trainer_id,
				'status' => 1,
			])
			->one();
			if(null!=$trainingScheduleTrainer){
				$available = false;
				$satker = $trainingScheduleTrainer->trainingSchedule->trainingClass->training->activity->satker->name;
				$training = $trainingScheduleTrainer->trainingSchedule->trainingClass->training->activity->name;
				$available_info = "This trainer have booked by ".$satker." for ".$training;
			}
		}	
		echo "</td>";
		echo "<td>";
		echo $tstr->trainer->person->phone;
		echo "</td>";
		echo "<td>";
		echo $tstr->trainer->person->organisation;
		echo "</td>";
		echo "<td>";
			if($available){
				echo "<input type='checkbox' name='trainer_id_array[".$tstr->trainer_id."]'";
			}
			else{
				echo Html::a('-', '#', 
							[
							'class' => 'label label-warning',
							'data-pjax'=>0,
							'title'=>$available_info,
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
							]
				);
			}
		echo "</td>";
		echo "</tr>";		
	}
	echo "</table>";

	?>
	<div class="well">
	<p>	Data diatas adalah data pengajar yang telah direkomendasikan oleh bidang renbang diklat	dan 
	tidak sedang mengajar ditempat lain dalam waktu yang sama</p>
    </div>
	<?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
    <?= Html::submitButton(
		'<span class="fa fa-fw fa-save"></span> Add', 
		['class' => 'btn btn-primary']) ?>
	
    <?php ActiveForm::end(); ?>
	</div>
</div>
</div>
<?php
$this->registerJs('
$("[data-toggle=\'tooltip\']").tooltip();
');
?>
