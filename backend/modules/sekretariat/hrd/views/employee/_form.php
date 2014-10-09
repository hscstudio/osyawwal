<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Person;
use backend\models\User;
use backend\models\Reference;
use backend\models\Organisation;
use kartik\widgets\SwitchInput;
/* @var $this yii\web\View */
/* @var $model backend\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	
	<?php
	$data = ArrayHelper::map(Person::find()
			->select(['id', 'name'])
			->asArray()
			->all()
			, 'id', 'name');
	if($model->isNewRecord) $model->person_id = $person_id;
	echo $form->field($model, 'person_id')->widget(Select2::classname(), [
		'data' => $data,
		'options' => ['placeholder' => 'Choose person ...'],
		'pluginOptions' => [
		'allowClear' => true
		],
	]); ?>
	
	<?php
	$data = ArrayHelper::map(User::find()
			->select(['id', 'username'])
			->asArray()
			->all()
			, 'id', 'username');

	echo $form->field($model, 'user_id')->widget(Select2::classname(), [
		'data' => $data,
		'options' => ['placeholder' => 'Choose user ...'],
		'pluginOptions' => [
		'allowClear' => true
		],
	]); ?>
	
	<div class="row clearfix">
		<div class="col-md-4">
		<?php
		$data = ArrayHelper::map(Reference::find()
				->select(['id', 'name'])
				->where(['type'=>'satker'])
				->asArray()
				->all()
				, 'id', 'name');		
		echo $form->field($model, 'satker_id')->widget(Select2::classname(), [
			'data' => $data,
			'options' => ['placeholder' => 'Choose satker ...'],
			'pluginOptions' => [
			'allowClear' => true
			],
		])->label(); 
		 ?> 
		</div>
		<div class="col-md-4">
			<?php
			$data = ArrayHelper::map(Organisation::find()
					->select(['ID', 'NM_UNIT_ORG'])
					->where(['JNS_KANTOR'=>13])
					->asArray()
					->all()
					, 'ID', 'NM_UNIT_ORG');

			echo $form->field($model, 'organisation_id')->widget(Select2::classname(), [
				'data' => $data,
				'options' => ['placeholder' => 'Choose organisation ...'],
				'pluginOptions' => [
				'allowClear' => true
				],
			]); ?>
		</div>
		<div class="col-md-4">
		<?= $form->field($model, 'chairman')->widget(SwitchInput::classname(), [
			'pluginOptions' => [
				'onText' => 'On',
				'offText' => 'Off',
			]
		]) ?> 
		</div>
		
	</div>
	<div class="row clearfix">
	<?php
	foreach($object_references_array as $object_reference=>$label){;
		$data = ArrayHelper::map(Reference::find()
				->select(['id', 'name'])
				->where(['type'=>$object_reference])
				->asArray()
				->all()
				, 'id', 'name');
		echo '<div class="col-md-4">';			
		echo $form->field(${$object_reference}, '['.$object_reference.']reference_id')->widget(Select2::classname(), [
			'data' => $data,
			'options' => ['placeholder' => 'Choose '.$label.' ...'],
			'pluginOptions' => [
			'allowClear' => true
			],
		])->label($label); 
		echo '</div>';
	}
	?>
	</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	<?php $this->registerCss('label{display:block !important;}'); ?>
</div>
