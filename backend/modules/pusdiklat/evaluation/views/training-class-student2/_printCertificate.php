<?php
use yii\helpers\Html; 
use kartik\widgets\ActiveForm; 
use kartik\widgets\Select2; 
use yii\helpers\ArrayHelper; 
use kartik\widgets\DatePicker;
?> 
<div class="row" style="margin:0;">
	
	<hr>
	<div class="col-md-12">
	<?php
	echo Html::a('<i class="fa fa-fw fa-print"></i> Checklist Data Peserta', ['print-student-checklist','tb_training_id'=>$tb_training_id,'tb_training_class_id'=>$tb_training_class_id], ['class' => 'btn btn-default','data-pjax'=>0]);
	?>
	</div>
	
	<div class="clearfix"></div>
	<hr>
	<div class="col-md-6">
	<?php $form = ActiveForm::begin([
		'id' => 'login-form',
		'action' => ['print-frontend-certificate','tb_training_id'=>$tb_training_id,'tb_training_class_id'=>$tb_training_class_id],
		'options' => [
			'class' => 'form-horizontal',					
		],
	]) ?>
		<?php //Html::input( $type, $name = null, $value = null, $options = [] ) ?>
		
		<table class="table table-condensed table-striped">
		<tr>
			<th colspan="2"><label>Frontend Certificate</label></th>
		</tr>
		<tr>
			<td>Certificate Type</td>
			<td>
			<?php
			$type_certificate=0;
			
			$name_training = trim($training->name);
			// DETECT DIKLAT
			
			if (strtoupper($name_training)===$name_training){
				$name_training = \yii\helpers\Inflector::camel2words($name_training);
			}
			if(strtoupper(substr($name_training,0,7))=='DIKLAT '){
				$name_training = trim(substr($name_training, 7 , 255)); // because in template Pendidikan dan Pelatihan
				$name_training = 'Pendidikan dan Pelatihan '.$name_training;
			}
			else if(strtoupper(substr($name_training,0,4))=='DTU '){
				$name_training = trim(substr($name_training, 4 , 255));
				$name_training = 'Pendidikan dan Pelatihan Teknis Umum '.$name_training;
			}
			else if(substr(strtoupper($name_training,0,5))=='DTSD '){
				$name_training = trim(substr($name_training, 5 , 255));
				$name_training = 'Pendidikan dan Pelatihan Teknis Substantif Dasar '.$name_training;
			}
			else if(substr(strtoupper($name_training,0,5))=='DTSS '){
				$name_training = trim(substr($name_training, 5 , 255));
				$name_training = 'Pendidikan dan Pelatihan Teknis Substantif Spesialisasi '.$name_training;
			}
			else if(strtoupper(substr($name_training,0,4))=='DFP '){
				$name_training = trim(substr($name_training, 4 , 255));
				$name_training = 'Pendidikan dan Pelatihan Fungsional Penjenjangan '.$name_training;
				$type_certificate = 1;
			}
			else if(strtoupper(substr($name_training,0,3))=='DF '){
				$name_training = trim(substr($name_training, 3 , 255));
				$name_training = 'Pendidikan dan Pelatihan Fungsional '.$name_training;
				$type_certificate = 1;
			}
			else if(strtoupper(substr($name_training,0,8))=='SEMINAR '){
				$name_training = str_replace('Seminar ','',$name_training);
				$type_certificate = 2;
			}
			?>
			
			<?php
			$program = \backend\models\ProgramHistory::find()
			->where([
				'tb_program_id'=>$training->tb_program_id,
				'revision'=>$training->tb_program_revision,
			])
			->one();
			
			if(in_array($program->number,[
				'1.0.0.0',  //PRAJAB
				'2.0.0.0',
				'2.1.0.0',
				'2.2.0.0',
				'2.2.1.0',
				'2.2.2.0',
				'2.2.3.0',
			])){
				$type_certificate=1;
			}
			
			if(in_array($program->number,[
				'2.3.0.0',
				'2.3.1.0',
				'2.3.1.1',
				'2.3.1.2',
				'2.3.2.0',
				'2.6.0.0', // PENYEGARAN
			])){
				$type_certificate=0;
			}
			
			if(in_array($program->number,[
				'2.4.0.0',
				'2.5.0.0',
				'3.0.0.0',
			])){
				$type_certificate='';
			}

			echo Select2::widget([
				'name' => 'type_certificate', 
				'data' => [
					'0'=>'Sertifikat',
					'1'=>'Surat Tanda Tamat Pendidikan dan Pelatihan',
					'2'=>'Seminar',
				],
				'options' => [
					'placeholder' => 'type ...', 
					'class'=>'form-control',	
					'id' => 'select2-type_certificate'
				],
			]); ?>
			
			<?php 
			if(!empty($type_certificate)){
				$this->registerJs('
					$("#select2-type_certificate").select2().select2("val", '.$type_certificate.');
				');
			}
			?>
			</td>
		</tr>
		<tr>
			<td>Training Name</td>
			<td><?= Html::input( 'text', 'name_training', $name_training, ['class'=>'form-control'] ) ?></td>
		</tr>
		<tr>
			<td>Training Location</td>
			<td><?= Html::input( 'text', 'location_training', 'Jakarta', ['class'=>'form-control'] ) ?></td>
		</tr>
		<tr>
			<td>Signer</td>
			<td>
			<?php
			$ref_satker_id = Yii::$app->user->identity->employee->ref_satker_id;
			$modelEmployeeSigners = \backend\models\Employee::find()
					->where(
						'
						(ref_satker_id=:ref_satker_id AND position <> 5) 
							or 
						(position=1)',
						[
							':ref_satker_id'=>$ref_satker_id,
						]
					)
					->asArray()
					->all();
					
			$employeeSigners = [];
			foreach($modelEmployeeSigners as $modelEmployeeSigner){
				$employeeSigners[$modelEmployeeSigner['id']]=$modelEmployeeSigner['name'].' - '.$modelEmployeeSigner['positionDesc'];
			}					
			
			echo Select2::widget([
				'name' => 'signer', 
				'data' => $employeeSigners,
				'options' => [
					'placeholder' => 'signer ...', 
					'class'=>'form-control',	
				],
			]); 
			?>
			</td>
		</tr>
		<tr>
			<td>City Signer</td>
			<td><?= Html::input( 'text', 'city_signer', (strlen($training->satker->city)>2)?$training->satker->city:'Jakarta', ['class'=>'form-control'] ) ?></td>
		</tr>
		<tr>
			<td></td>
			<td><?= Html::submitButton('<i class="fa fa-print"></i> Print', ['class' => 'btn btn-primary']) ?></td>
		</tr>
		</table>

	<?php ActiveForm::end() ?>
	</div>
	<div class="col-md-6">
	
	<?php $form2 = ActiveForm::begin([
		'id' => 'print-form-2',
		'action' => ['print-backend-certificate','tb_training_id'=>$tb_training_id,'tb_training_class_id'=>$tb_training_class_id],
		'options' => [
			'class' => 'form-horizontal',
		],
	]) ?>
		<?php //Html::input( $type, $name = null, $value = null, $options = [] ) ?>
		
		<table class="table table-condensed table-striped">
		<tr>
			<th colspan="2"><label>Backend Certificate</label></th>
		</tr>
		<tr>
			<td style="width:30%">Tanggal</td>
			<td>
			<?php 
			echo DatePicker::widget([
				'name' => 'date',
				'type' => DatePicker::TYPE_COMPONENT_PREPEND,
				//'value' => '23-Feb-1982',
				'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'dd-M-yyyy'
				]
				]); 
			?>
			</td>
		</tr>
		<tr>
			<td>Signer</td>
			<td>
			<?php
			$ref_satker_id = Yii::$app->user->identity->employee->ref_satker_id;
			$modelEmployeeSigners = \backend\models\Employee::find()
					->where(
						'
						(ref_satker_id=:ref_satker_id AND position <> 5) 
							or 
						(position=1)',
						[
							':ref_satker_id'=>$ref_satker_id,
						]
					)
					->asArray()
					->all();
					
			$employeeSigners = [];
			foreach($modelEmployeeSigners as $modelEmployeeSigner){
				$employeeSigners[$modelEmployeeSigner['id']]=$modelEmployeeSigner['name'].' - '.$modelEmployeeSigner['positionDesc'];
			}			
			echo Select2::widget([
				'name' => 'signer', 
				'data' => $employeeSigners,
				'options' => [
					'placeholder' => 'signer ...', 
					'class'=>'form-control',	
				],
			]); 
			?>
			</td>
		</tr>
		<tr>
			<td>City Signer</td>
			<td><?= Html::input( 'text', 'city_signer', (strlen($training->satker->city)>2)?$training->satker->city:'Jakarta', ['class'=>'form-control'] ) ?></td>
		</tr>
		<tr>
			<td></td>
			<td><?= Html::submitButton('<i class="fa fa-print"></i> Print', ['class' => 'btn btn-success']) ?></td>
		</tr>
		</table>

	<?php ActiveForm::end() ?>
	
	<hr>
	
	<?php $form3 = ActiveForm::begin([
		'id' => 'print-form-3',
		'action' => ['print-value-certificate','tb_training_id'=>$tb_training_id,'tb_training_class_id'=>$tb_training_class_id],
		'options' => [
			'class' => 'form-horizontal',
		],
	]) ?>
		<?php //Html::input( $type, $name = null, $value = null, $options = [] ) ?>
		
		<table class="table table-condensed table-striped">
		<tr>
			<th colspan="2"><label>Form Lampiran Nilai</label></th>
		</tr>
		<tr>
			<td style="width:30%">Tanggal</td>
			<td>
			<?php 
			echo DatePicker::widget([
				'name' => 'date',
				'type' => DatePicker::TYPE_COMPONENT_PREPEND,
				//'value' => '23-Feb-1982',
				'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'dd-M-yyyy'
				]
				]); 
			?>
			</td>
		</tr>
		<tr>
			<td>Signer</td>
			<td>
			<?php
			$ref_satker_id = Yii::$app->user->identity->employee->ref_satker_id;
			$modelEmployeeSigners = \backend\models\Employee::find()
					->where(
						'
						(ref_satker_id=:ref_satker_id AND position <> 5) 
							or 
						(position=1)',
						[
							':ref_satker_id'=>$ref_satker_id,
						]
					)
					->asArray()
					->all();
					
			$employeeSigners = [];
			foreach($modelEmployeeSigners as $modelEmployeeSigner){
				$employeeSigners[$modelEmployeeSigner['id']]=$modelEmployeeSigner['name'].' - '.$modelEmployeeSigner['positionDesc'];
			}			
			echo Select2::widget([
				'name' => 'signer', 
				'data' => $employeeSigners,
				'options' => [
					'placeholder' => 'signer ...', 
					'class'=>'form-control',	
				],
			]); 
			?>
			</td>
		</tr>
		<tr>
			<td>City Signer</td>
			<td><?= Html::input( 'text', 'city_signer', (strlen($training->satker->city)>2)?$training->satker->city:'Jakarta', ['class'=>'form-control'] ) ?></td>
		</tr>
		<tr>
			<td></td>
			<td><?= Html::submitButton('<i class="fa fa-print"></i> Print', ['class' => 'btn btn-success']) ?></td>
		</tr>
		</table>

	<?php ActiveForm::end() ?>
	
	</div>
	
	<div class="clearfix"></div>
	<hr>
	<div class="col-md-12">
	<?php
	echo Html::a('<i class="fa fa-fw fa-print"></i> Tanda Terima Sertifikat', ['print-certificate-receipt','tb_training_id'=>$tb_training_id,'tb_training_class_id'=>$tb_training_class_id], ['class' => 'btn btn-default','data-pjax'=>0]);
	?>
	</div>
	<div class="clearfix"></div>
	<hr>
</div>
		