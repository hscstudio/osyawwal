<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Person;
use backend\models\Employee;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="program-form">
    <?php $form = ActiveForm::begin([
		'action' => ['pic','id'=>$model->id],
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
							url: '".Url::to(['index'])."',
							container: '#pjax-program-gridview', 
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
    <?php //$form->field($model, 'number')->textInput(['maxlength' => 15]) ?>
	
	<?php if(!$model->isNewRecord){ ?>	
	<?php
	foreach($object_people_array as $object_person=>$label){;
		$data = ArrayHelper::map(Person::find()
				->select(['id', 'name'])
				->where([
					'id'=>Employee::find()
						->select('person_id')
						->where([
							'organisation_id'=>393, // CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
						])
						->currentSatker()
						->column(),
				])		
				->active()
				->asArray()
				->all()
				, 'id', 'name');

		echo $form->field(${$object_person}, '['.$object_person.']person_id')->widget(Select2::classname(), [
			'data' => $data,
			'options' => ['placeholder' => 'Choose '.$label.' ...'],
			'pluginOptions' => [
			'allowClear' => true
			],
		])->label($label); 
	}
	?>
	<?php } ?>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	
	<?php $this->registerCss('label{display:block !important;}'); ?>
</div>
