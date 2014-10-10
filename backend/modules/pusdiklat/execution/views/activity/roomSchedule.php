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
		<strong>Set Room</strong>
	</div>
	<div style="margin:10px">
    <?php $form = ActiveForm::begin([
		'action' => ['room-class-schedule','id'=>$activity->id,'class_id'=>$class->id,'schedule_id'=>$model->id],
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
	
	echo $form->field($model, 'activity_room_id')->widget(Select2::classname(), [
		'data' => $dataRoom,
		'options' => ['placeholder' => 'Choose Room ...'],
		'pluginOptions' => [
		'allowClear' => true
		],
	])->label('Room'); ?>
	
	<hr>
	
	Ket: <br>
	Data diatas adalah data ruangan yang telah dibooking dan disetujui oleh pemilik ruangan	
    <hr>
    <?= Html::submitButton(
		'<span class="fa fa-fw fa-save"></span> Update', 
		['class' => 'btn btn-success']) ?>
	
    <?php ActiveForm::end(); ?>
	</div>
</div>
</div>
