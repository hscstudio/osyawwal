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
/* @var $model backend\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin([
		'options'=>['enctype'=>'multipart/form-data']
	]); ?>
	
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	
	<ul class="nav nav-tabs" role="tablist" id="tab_wizard">
		<li class="active"><a href="#personal_information" role="tab" data-toggle="tab">Personal</a></li>
		<li class=""><a href="#contact_information" role="tab" data-toggle="tab">Contact</a></li>
		<li class=""><a href="#employee_information" role="tab" data-toggle="tab">Employee</a></li>
		<li class=""><a href="#office_information" role="tab" data-toggle="tab">Office</a></li>
		<li class=""><a href="#education_information" role="tab" data-toggle="tab">Education</a></li>
		<li class=""><a href="#photo_document" role="tab" data-toggle="tab">Photo & Document</a></li>
	</ul>
	<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:10px; background-color: #fff;">
		<?php
		foreach($object_references_array as $object_reference=>$label){;
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
				<?= $form->field($model, 'front_title')->textInput(['maxlength' => 25]) ?>
				</div>
				<div class="col-md-6">
				<?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>
				</div>
				<div class="col-md-3">
				<?= $form->field($model, 'back_title')->textInput(['maxlength' => 25]) ?>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-3">
				<?= $form->field($model, 'nickname')->textInput(['maxlength' => 25]) ?>
				</div>
				<div class="col-md-3">
				<?= $form->field($model, 'gender')->widget(SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'Male',
						'offText' => 'Female',
					]
				]) ?> 
				</div>
			</div>						
			<div class="row clearfix">
				<div class="col-md-3">
				<?= $form->field($model, 'born')->textInput(['maxlength' => 50]) ?>
				</div>
				<div class="col-md-3">
				<?= $form->field($model, 'birthday')->widget(DateControl::classname(), [
						'type' => DateControl::FORMAT_DATE,
					]); ?>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-3">
				<?= $form->field($model, 'married')->widget(SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'On',
						'offText' => 'Off',
					]
				]) ?> 
				</div>
				<div class="col-md-3">
				<?= $form->field($model, 'blood')->textInput(['maxlength' => 25]) ?>
				</div>
				<div class="col-md-3">
				<?= $field['religion'] ?> 
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-4">
				<?= $form->field($model, 'nid')->textInput(['maxlength' => 100]) ?>
				</div>
				<div class="col-md-4">
				<?= $form->field($model, 'npwp')->textInput(['maxlength' => 100]) ?>
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
				<?= $form->field($model, 'phone')->textInput(['maxlength' => 50]) ?>
				</div>
				<div class="col-md-4">
				<?= $form->field($model, 'email')->textInput(['maxlength' => 100]) ?>
				</div>
			</div>

			<?= $form->field($model, 'homepage')->textInput(['maxlength' => 255]) ?>

			<?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>
			
			<?= $form->field($model, 'bank_account')->textInput(['maxlength' => 255]) ?>
			
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
				<?= $form->field($model, 'nip')->textInput(['maxlength' => 25])->label('NIP') ?>	
				</div>		
			</div>
			
			<?= $field['rank_class'] ?> 
			
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
				echo $form->field($model, 'position')->widget(Select2::classname(), [
					'data' => $data,
					'options' => ['placeholder' => 'Choose position ...'],
					'pluginOptions' => [
					'allowClear' => true
					],
				]); ?>
				</div>
				<div class="col-md-8">
				<?= $form->field($model, 'position_desc')->textInput(['maxlength' => 255]) ?>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-4">
				<?= $field['unit'] ?> 
				</div>
				<div class="col-md-8">
				<?= $form->field($model, 'organisation')->textInput(['maxlength' => 255]) ?>
				</div>
			</div>

			<div class="row clearfix">
				<div class="col-md-4">
				<?= $form->field($model, 'office_phone')->textInput(['maxlength' => 50]) ?>
				</div>
				<div class="col-md-4">
				<?= $form->field($model, 'office_fax')->textInput(['maxlength' => 50]) ?>
				</div>
				<div class="col-md-4">
				<?= $form->field($model, 'office_email')->textInput(['maxlength' => 100]) ?>
				</div>
			</div>

			<?= $form->field($model, 'office_address')->textInput(['maxlength' => 255]) ?>
			
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
				<?= $form->field($model, 'graduate_desc')->textInput(['maxlength' => 255]) ?>
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
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','onclick'=>'if($("#agreement_status").prop("checked")==false){ alert("Anda harus menyetujui Pakta Integritas!"); return false; }']) ?>
		</div>
	</div>
	<div class="clearfix"><hr></div>

    <?= $form->field($model, 'status')->widget(SwitchInput::classname(), [
		'pluginOptions' => [
			'onText' => 'On',
			'offText' => 'Off',
		]
	]) ?> 

    <?php ActiveForm::end(); ?>

	<?php $this->registerCss('label{display:block !important;}'); ?>
</div>
