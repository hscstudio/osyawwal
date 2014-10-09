<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'PIC {modelClass}: ', [
    'modelClass' => 'Training',
]) . ' ' . Inflector::camel2words($activity->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activity'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Inflector::camel2words($activity->name), 'url' => ['view', 'id' => $activity->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'PIC');
?>
<div class="program-update panel panel-default">
	<?php
	if (!Yii::$app->request->isAjax) {
	?>
    <div class="panel-heading">		
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['class-student','id'=>$model->id], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<?php
	}
	?>
	<div class="panel-body">
		<?php
		/* @var $this yii\web\View */
		/* @var $model backend\models\Program */
		/* @var $form yii\widgets\ActiveForm */
		?>

		<div class="program-form">
			<?php $form = ActiveForm::begin([
				'action' => ['change-class-student','id'=>$activity->id,'class_id'=>$class->id,'training_class_student_id'=>$model->id,],
				'enableAjaxValidation' => false,
				'enableClientValidation' => false,
				'options'=>[
					'onsubmit'=>"
						$.ajax({
							url: $(this).attr('action'),
							type: 'post',
							data: $(this).serialize(),
							success: function(data) {
								$.growl({
									icon: 'glyphicon glyphicon-info-sign',
									title: ' '+data
								});

								$('#modal-heart').modal('hide');
								
								$.pjax.reload({
									url: '".Url::to(['class-student','id'=>$activity->id,'class_id'=>$class->id])."',
									container: '#pjax-gridview', 
									timeout: 1,
								});				
													
															
							},
							error:  function( jqXHR, textStatus, errorThrown ) {
								alert(jqXHR.responseText);
							}
						});	
						return false;
					",
				],
			]); ?>
			
			<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
			<?php
			$person = $model->trainingStudent->student->person;
			echo '<strong>'.$person->name.' - '.$person->nip.'</strong>'; ?> <!-- ADDED HERE -->
			<hr>
			<?php
			$data = ArrayHelper::map($trainingClass,'id', 'class');
			echo $form->field($model, 'training_class_id')->widget(Select2::classname(), [
				'data' => $data,
				'options' => ['placeholder' => 'Choose class ...'],
				'pluginOptions' => [
					'allowClear' => true
				],
			]);
			?>
			
			<div class="form-group">
				<?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
			</div>

			<?php ActiveForm::end(); ?>
			
			<?php $this->registerCss('label{display:block !important;}'); ?>
		</div>
	</div>
</div>
