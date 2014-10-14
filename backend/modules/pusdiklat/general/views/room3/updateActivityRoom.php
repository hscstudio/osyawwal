<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Room */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Update Activity {modelClass}: ', [
    'modelClass' => 'Room',
]) . ' ' . $model->room->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rooms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->room->name, 'url' => ['view', 'id' => $model->room->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="room-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['activity-room','id'=>$id], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<?php 
		$options=[];
		if (Yii::$app->request->isAjax){		
			$options['enableAjaxValidation']=false;
			$options['enableClientValidation']=true;
			/* $options['beforeSubmit']="function(form) {
				if($(form).find('.has-error').length) {
					return false;
				}					
				$.ajax({
					url: form.attr('action'),
					type: 'post',
					data: form.serialize(),
					success: function(data) {
						$.pjax.reload({
							url: '".\yii\helpers\Url::to(['calendar-activity-room','id'=>$model->room->id])."', 
							container: '#pjax-gridview', 
							timeout: 3000,
						});
						$.growl(data, {	type: 'success'	});
						$('#modal-heart').modal('hide');
					}
				});					
				return false;
			}"; */
		}
		
		$form = ActiveForm::begin([
			'id'=>'formUpdate',
		]); ?>
		<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
		
		<?php
		$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		if($model->room->satker_id==$satker_id){
			/* $data = ArrayHelper::map(\backend\models\Room::find()
				->select(['id','name'])
				->where('satker_id=:satker_id and status=1',[':satker_id'=>$satker_id])
				->asArray()
				->all(), 'id', 'name');
			echo $form->field($model, 'room_id')->widget(Select2::classname(), [
				'data' => $data,
				'options' => ['placeholder' => 'Choose Room ...'],
				'pluginOptions' => [
				'allowClear' => true
				],
			]);  */
			echo $form->field($model, 'start')->widget(\kartik\datecontrol\DateControl::classname(), [
						'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
					]);

			echo $form->field($model, 'end')->widget(\kartik\datecontrol\DateControl::classname(), [
						'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
					]); 
		}
		?>


		<?php
		
		echo $form->field($model, 'status')->widget(Select2::classname(), [
			'data' => $data_status,
			'options' => ['placeholder' => 'Choose status ...'],
			'pluginOptions' => [
			'allowClear' => true
			],
		]); 
		
		if($model->room->satker_id==Yii::$app->user->identity->employee->satker_id){
			echo $form->field($model, 'note')->textInput(['maxlength' => 255]);
		}
		?>
		
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
		
		<?php 
		if(!$model->isNewRecord){
			echo '<input type="hidden" name="redirect" value="calendar">';
		}
		ActiveForm::end(); 
		?>
		
		<?php
		$this->registerCss('label{display:block !important;}'); 
		$js = "
		$('form#formUpdate').on('beforeSubmit', function(e, \$form) {
			var form = $(this)
		   $.ajax({
				url: form.attr('action'),
				type: 'post',
				data: form.serialize(),
				success: function(data) {
					$.pjax.reload({
						url: '".\yii\helpers\Url::to(['calendar-activity-room','id'=>$model->room_id])."', 
						container: '#pjax-calendar', 
						timeout: 3000,
					});
					$.growl(data, {	type: 'success'	});
					$('#modal-heart').modal('hide');
				}				
			});	
			return false;
		}).on('submit', function(e){
			e.preventDefault();
		});
		";
		$this->registerJs($js);
		?>
	</div>
</div>
