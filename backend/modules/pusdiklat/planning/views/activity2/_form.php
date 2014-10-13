<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use kartik\widgets\SwitchInput;
use backend\models\Reference;
use backend\models\Program;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model backend\models\Activity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	
	<ul class="nav nav-tabs" role="tablist" id="tab_wizard">
		<li class="active"><a href="#activity" role="tab" data-toggle="tab">Activity <span class='label label-info'>1</span></a></li>
		<li class=""><a href="#training" role="tab" data-toggle="tab">Training <span class='label label-warning'>2</span></a></li>
	</ul>
	<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:10px; background-color: #fff;">
		<div class="tab-pane fade-in active" id="activity">
			<?php
			$data = ArrayHelper::map(
				Program::find()
					->select([
						'id','name', 'num_name' => 'CONCAT(number," - ",name)'
					])
					->currentSatker()
					->active()
					->asArray()
					->all(), 'id', 'num_name');
					
			echo $form->field($training, 'program_id')->widget(Select2::classname(), [
				'data' => $data,
				'options' => [
					'placeholder' => 'Choose Program ...',
					'onchange'=>'
						$.post( "'.Url::to(['program-name']).'?id="+$(this).val(), function( data ) {
						  $( "input#activity-name" ).val( data + " ");
						  $( "input#activity-name" ).focus();
						});
					'
				],
				'pluginOptions' => [
					'allowClear' => true,
				],
			]); ?>
			
			
			<?php
			if (!$model->isNewRecord){ 
				echo $form->field($training, 'program_revision')->widget(Select2::classname(), [
					'data' => $program_revisions, 
					'options' => [
						'placeholder' => 'Choose revision ...',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				]);
			}
			?>
			
			<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

			<?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
			
			<?= $form->field($model, 'hostel')->widget(SwitchInput::classname(), [
				'pluginOptions' => [
					'onText' => 'Ya',
					'offText' => 'Tidak',
				]
			])->label('Diasramakan?') ?>
			
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#training]').tab('show')">
				Next 
				<i class="fa fa-fw fa-arrow-circle-o-right"></i>
			</a>
		</div>
		<div class="tab-pane fade" id="training">				
			<div class="row clearfix">
				<div class="col-md-2">
				<?= $form->field($training, 'regular')->widget(SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'Ya',
						'offText' => 'Tidak',
					]
				]) ?>
				</div>
				<div class="col-md-10">
				<?= $form->field($training, 'stakeholder')->textInput(['maxlength' => 255]) ?>
				</div>
			</div>		
			
			<?= $form->field($training, 'cost_source')->textInput(['maxlength' => 255]) ?>

			<?= $form->field($training, 'cost_plan')->textInput(['maxlength' => 15]) ?>			
			
			<?= $form->field($training, 'note')->textInput(['maxlength' => 255]) ?>
			
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#activity]').tab('show')">
				Previous 
				<i class="fa fa-fw fa-arrow-circle-o-left"></i>
			</a>
			
			<div class="clearfix"><hr></div>  
			
			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>
			<?php // PENYELENGGARAAN ONLY ?>
			<?php // $form->field($training, 'execution_sk')->textInput(['maxlength' => 255]) ?>
			<?php // $form->field($training, 'cost_real')->textInput(['maxlength' => 15]) ?>
			
			<?php // EVALUASI ONLY ?>
			<?php // $form->field($training, 'result_sk')->textInput(['maxlength' => 255]) ?>		
			
			<?php // BDK ONLY ?>
			<?php // $form->field($training, 'approved_status')->textInput() ?>
			<?php // $form->field($training, 'approved_note')->textInput(['maxlength' => 255]) ?>
			<?php // $form->field($training, 'approved_date')->textInput() ?>
			<?php // $form->field($training, 'approved_by')->textInput() ?>
		</div>
	</div>
	 

    <?php ActiveForm::end(); ?>
	<?php $this->registerCss('label{display:block !important;}'); ?>

</div>
