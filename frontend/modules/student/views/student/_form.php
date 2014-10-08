<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use kartik\widgets\SwitchInput;
use backend\models\Reference;
use kartik\widgets\FileInput;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model frontend\models\Student */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="student-form">

    <?php $form = ActiveForm::begin([
		'options'=>['enctype'=>'multipart/form-data']
	]); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	<?php $form->field($model, 'username')->textInput(['disabled' => 'disabled','id'=>'','name'=>'']) ?>
	<ul class="nav nav-tabs" role="tablist" id="tab_wizard">
		<li class="active"><a href="#personal_information" role="tab" data-toggle="tab">Personal</a></li>
		<li class=""><a href="#contact_information" role="tab" data-toggle="tab">Contact</a></li>
		<li class=""><a href="#employee_information" role="tab" data-toggle="tab">Employee</a></li>
		<li class=""><a href="#office_information" role="tab" data-toggle="tab">Office</a></li>
		<li class=""><a href="#education_information" role="tab" data-toggle="tab">Education</a></li>
		<li class=""><a href="#photo_document" role="tab" data-toggle="tab">Photo & Document</a></li>
		<li class=""><a href="#agreement" role="tab" data-toggle="tab">Agreement</a></li>
	</ul>
	<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:10px; background-color: #fff;">
		<?php
		foreach($object_references_array as $object_reference=>$label){
			$data = ArrayHelper::map(Reference::find()
					->select(['id', 'name'])
					->where(['type'=>$object_reference])
					->asArray()
					->all()
					, 'id', 'name');
					
			$field[$object_reference] = $form->field(${$object_reference}, '['.$object_reference.']reference_id')->widget(Select2::classname(), [
				'data' => $data,
				'options' => ['placeholder' => 'Choose '.$label.' ...'],
				'pluginOptions' => [
				'allowClear' => true
				],
			])->label($label); 
		}
		?>
		<div class="tab-pane fade-in active" id="personal_information">
			<h3>Informasi Pribadi</h3>
			<div class="row clearfix">
				<div class="col-md-3">
				<?= $form->field($person, 'front_title')->textInput(['maxlength' => 25]) ?>
				</div>
				<div class="col-md-6">
				<?= $form->field($person, 'name')->textInput(['maxlength' => 100]) ?>
				</div>
				<div class="col-md-3">
				<?= $form->field($person, 'back_title')->textInput(['maxlength' => 25]) ?>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-3">
				<?= $form->field($person, 'nickname')->textInput(['maxlength' => 25]) ?>
				</div>
				<div class="col-md-3">
				<?= $form->field($person, 'gender')->widget(SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'Male',
						'offText' => 'Female',
					]
				]) ?> 
				</div>
			</div>						
			<div class="row clearfix">
				<div class="col-md-3">
				<?= $form->field($person, 'born')->textInput(['maxlength' => 50]) ?>
				</div>
				<div class="col-md-3">
				<?= $form->field($person, 'birthday')->widget(DateControl::classname(), [
						'type' => DateControl::FORMAT_DATE,
					]); ?>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-3">
				<?= $form->field($person, 'married')->widget(SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'On',
						'offText' => 'Off',
					]
				]) ?> 
				</div>
				<div class="col-md-3">
				<?= $form->field($person, 'blood')->textInput(['maxlength' => 25]) ?>
				</div>
				<div class="col-md-3">
				<?= $field['religion'] ?> 
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-4">
				<?= $form->field($person, 'nid')->textInput(['maxlength' => 100]) ?>
				</div>
				<div class="col-md-4">
				<?= $form->field($person, 'npwp')->textInput(['maxlength' => 100]) ?>
				</div>
			</div>						
			
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#contact_information]').tab('show')">
				Next 
				<i class="fa fa-fw fa-arrow-circle-o-right"></i>
			</a>
		</div>
		<div class="tab-pane fade" id="contact_information">
			
			<h3>Informasi Kontak</h3>
			
			<div class="row clearfix">
				<div class="col-md-4">
				<?= $form->field($person, 'phone')->textInput(['maxlength' => 50]) ?>
				</div>
				<div class="col-md-4">
				<?= $form->field($person, 'email')->textInput(['maxlength' => 100]) ?>
				</div>
			</div>

			<?= $form->field($person, 'homepage')->textInput(['maxlength' => 255]) ?>

			<?= $form->field($person, 'address')->textInput(['maxlength' => 255]) ?>
			
			<?= $form->field($person, 'bank_account')->textInput(['maxlength' => 255]) ?>
			
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#personal_information]').tab('show')">
				Previous 
				<i class="fa fa-fw fa-arrow-circle-o-left"></i>
			</a>
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#employee_information]').tab('show')">
				Next 
				<i class="fa fa-fw fa-arrow-circle-o-right"></i>
			</a>
			
		</div>
		<div class="tab-pane fade" id="employee_information">
			
			<h3>Informasi Kepegawaian (PNS Only)</h3>
			
			<div class="row clearfix">
				<div class="col-md-3">
				<?= $form->field($person, 'nip')->textInput(['maxlength' => 25])->label('NIP') ?>	
				</div>		
			</div>
			
			<?= $field['rank_class'] ?> 
			
			<?= $field['unit'] ?> 
			
			<?= $form->field($model, 'eselon2')->textInput(['maxlength' => 255]) ?>
			
			<?= $form->field($model, 'eselon3')->textInput(['maxlength' => 255]) ?>
			
			<?= $form->field($model, 'eselon4')->textInput(['maxlength' => 255]) ?>
			
			<div class="row clearfix">
				<div class="col-md-3">
				<?php
				$data = ['2' => 'Eselon 2','3' => 'Eselon 3','4' => 'Eselon 4'];
				echo $form->field($model, 'satker')->widget(Select2::classname(), [
					'data' => $data,
					'options' => ['placeholder' => 'Choose satker ...'],
					'pluginOptions' => [
					'allowClear' => true
					],
				]); ?>
				</div>		
			</div>	
			<div class="row clearfix">
				<div class="col-md-4">
				<?= $form->field($model, 'no_sk')->textInput() ?>
				</div>
				<div class="col-md-4">
				<?= $form->field($model, 'tmt_sk')->textInput() ?>
				</div>
			</div>
			
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#contact_information]').tab('show')">
				Previous 
				<i class="fa fa-fw fa-arrow-circle-o-left"></i>
			</a>
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#office_information]').tab('show')">
				Next 
				<i class="fa fa-fw fa-arrow-circle-o-right"></i>
			</a>
			
		</div>
		<div class="tab-pane fade" id="office_information">
		
			<h3>Informasi Kantor</h3>
			
			<div class="row clearfix">
				<div class="col-md-4">
				<?php
				$data = ['5' => 'Pelaksana / Others','4' => 'Eselon 4','3' => 'Eselon 3','2' => 'Eselon 2','1' => 'Eselon 1'];
				echo $form->field($person, 'position')->widget(Select2::classname(), [
					'data' => $data,
					'options' => ['placeholder' => 'Choose position ...'],
					'pluginOptions' => [
					'allowClear' => true
					],
				]); ?>
				</div>
				<div class="col-md-8">
				<?= $form->field($person, 'position_desc')->textInput(['maxlength' => 255]) ?>
				</div>
			</div>

			<?= $form->field($person, 'organisation')->textInput(['maxlength' => 255]) ?>

			<div class="row clearfix">
				<div class="col-md-4">
				<?= $form->field($person, 'office_phone')->textInput(['maxlength' => 50]) ?>
				</div>
				<div class="col-md-4">
				<?= $form->field($person, 'office_fax')->textInput(['maxlength' => 50]) ?>
				</div>
				<div class="col-md-4">
				<?= $form->field($person, 'office_email')->textInput(['maxlength' => 100]) ?>
				</div>
			</div>

			<?= $form->field($person, 'office_address')->textInput(['maxlength' => 255]) ?>
			
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#employee_information]').tab('show')">
				Previous 
				<i class="fa fa-fw fa-arrow-circle-o-left"></i>
			</a>
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#education_information]').tab('show')">
				Next 
				<i class="fa fa-fw fa-arrow-circle-o-right"></i>
			</a>
			
		</div>
		<div class="tab-pane fade" id="education_information">
		
			<h3>Informasi Pendidikan & Pengalaman</h3>
			
			<div class="row clearfix">
				<div class="col-md-4">
				<?= $field['graduate'] ?> 
				</div>
				<div class="col-md-8">
				<?= $form->field($person, 'graduate_desc')->textInput(['maxlength' => 255]) ?>
				</div>
			</div>
			
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#office_information]').tab('show')">
				Previous 
				<i class="fa fa-fw fa-arrow-circle-o-left"></i>
			</a>
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#photo_document]').tab('show')">
				Next 
				<i class="fa fa-fw fa-arrow-circle-o-right"></i>
			</a>
			
		</div>
		<div class="tab-pane fade" id="photo_document">
			<h3>Foto dan Dokumen</h3>
			<table class="table">
			<?php
			foreach($object_file_array as $object_file=>$label){
				if($object_file=='photo'){
					if(null!=${$object_file.'_file'}){
						?>
						<tr>
						<td>
						<div class="file-preview-thumbnails">
							<div class="file-preview-frame">						
								<img src="<?= Url::to(['/file/download','file'=>${$object_file}->object.'/'.${$object_file}->object_id.'/thumb_'.${$object_file.'_file'}->file_name]) ?>" class="file-preview-image">						
							</div>
						</div>
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
				else{
					if(null!=${$object_file.'_file'}){
						?>
						<tr>
						<td>
						<?php
						echo Html::a(
							${$object_file.'_file'}->file_name,
							Url::to(['/file/download','file'=>${$object_file}->object.'/'.${$object_file}->object_id.'/'.${$object_file.'_file'}->file_name]),
							[
							'class'=>'badge',
							]
						);
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
			}
			?>  
			</table>
			<div class="clearfix"></div>
			
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#education_information]').tab('show')">
				Previous 
				<i class="fa fa-fw fa-arrow-circle-o-left"></i>
			</a>
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#agreement]').tab('show')">
				Next 
				<i class="fa fa-fw fa-arrow-circle-o-right"></i>
			</a>
		</div>
		<div class="tab-pane fade" id="agreement">
			<h3>Pakta Integritas</h3>	
			<div class="well">
				Bahwa data yang saya isikan adalah data yang benar.
				<hr>
				Saya setuju <input type="checkbox" id="agreement_status">
			</div>
			<a class="btn btn-default" onclick="$('#tab_wizard a[href=#photo_document]').tab('show')">
				Previous 
				<i class="fa fa-fw fa-arrow-circle-o-left"></i>
			</a>
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','onclick'=>'if($("#agreement_status").prop("checked")==false){ alert("Anda harus menyetujui Pakta Integritas!"); return false; }']) ?>
		</div>
	</div>
	<div class="clearfix"><hr></div>
    

    <?php ActiveForm::end(); ?>

	<?php $this->registerCss('label{display:block !important;}'); ?>
</div>
