<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use backend\models\Reference;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="program-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
    <?php //$form->field($model, 'number')->textInput(['maxlength' => 15]) ?>
	
	<?php
	$data = ArrayHelper::map(
		\backend\models\Reference::find()
			->select(['id','value', 'CONCAT(name," => ",value) as name_value'])
			->where(['type'=>'program_code'])
			->orderBy(['sort'=> SORT_ASC])			
			->asArray()
			->all(), 
			'value', 'name_value'
	);
	
	echo $form->field($model, 'number')->widget(Select2::classname(), [
		'data' => $data,
		'options' => ['placeholder' => 'Choose code ...'],
		'pluginOptions' => [
			'allowClear' => true
		],
	]);
	?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
    
    <?= $form->field($model, 'note')->textInput(['maxlength' => 255]) ?>	
	
	<div class="row">
		<div class="col-md-3">
			<?= $form->field($model, 'hours')->textInput(['maxlength' => 5]) ?>
		</div>
		<div class="col-md-3">
			<?= $form->field($model, 'days')->textInput() ?>
		</div>
		<div class="col-md-3">
			<?= $form->field($model, 'test')->widget(SwitchInput::classname(), [
				'pluginOptions' => [
					'onText' => 'On',
					'offText' => 'Off',
				]
			]) ?>
		</div>
	</div>
		
	<?= $form->field($model, 'stage')->textInput(['maxlength' => 255]) ?>
	
	<?php
	$data = ['1'=>'Dasar','2'=>'Lanjutan','3'=>'Menengah','4'=>'Tinggi'];
	echo $form->field($model, 'category')->widget(Select2::classname(), [
		'data' => $data,
		'options' => [
			'placeholder' => 'Choose category ...',
		],
		'pluginOptions' => [
			'allowClear' => true,
		],
	]);
	?>
	
	<?php if(!$model->isNewRecord){ ?>
		<?= $form->field($model, 'status')->widget(SwitchInput::classname(), [
			'pluginOptions' => [
				'onText' => 'On',
				'offText' => 'Off',
			]
		]) ?>
	<?php } ?>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	
	<?php $this->registerCss('label{display:block !important;}'); ?>
</div>
