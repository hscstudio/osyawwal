<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use backend\models\Reference;
use kartik\widgets\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="program-form">

    <?php $form = ActiveForm::begin([
		'options'=>['enctype'=>'multipart/form-data']
	]); ?>
	
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
    <?php //$form->field($model, 'number')->textInput(['maxlength' => 15]) ?>
	
	<?php if(!$model->isNewRecord){ ?>
	
	<div class="row">
		<div class="col-md-3">
		<?php
		$data = ['0'=>'Draft','1'=>'Process','3'=>'Valid','4'=>'Reject'];
		echo $form->field($model, 'validation_status')->widget(Select2::classname(), [
			'data' => $data,
			'options' => [
				'placeholder' => 'Choose validation status ...',
			],
			'pluginOptions' => [
				'allowClear' => true,
			],
		]);
		?>
		</div>
		<div class="col-md-9">
		<?= $form->field($model, 'validation_note')->textInput(['maxlength' => 255]) ?>
		</div>
	</div>
	
	<table class="table">
	<?php
	foreach($object_file_array as $object_file=>$label){	
		if(null!=${$object_file.'_file'}){
			?>
			<tr>
			<td class="col-md-3">
			<?php
			if(empty(${$object_file.'_file'}->file_name)){
				?>
				<div class="form-group">
				<label class="control-label">Download</label>
				-
				</div>
				<?php
			}
			else{
				echo Html::a(
					${$object_file.'_file'}->file_name,
					Url::to(['/file/download','file'=>${$object_file}->object.'/'.${$object_file}->object_id.'/'.${$object_file.'_file'}->file_name]),
					[
					'class'=>'badge',
					]
				);
			}
			?>
			</td>
			<td>
			<?php						
			echo $form->field(${$object_file.'_file'}, 'file_name['.$object_file.']')->widget(FileInput::classname(), [
				'pluginOptions' => [
					'previewFileType' => 'any',
					'showUpload' => false,
				]
			])->label($label); 
			?>
			</td>
			</tr>
			<?php
		}
	}
	?>  
	</table>
	<?php } ?>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	
	<?php $this->registerCss('label{display:block !important;}'); ?>
</div>
