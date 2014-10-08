<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Reference;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Student */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="student-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->

    <?= $form->field($model, 'username')->textInput(['maxlength' => 25]) ?>
	
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

    <?= $form->field($model, 'eselon2')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'eselon3')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'eselon4')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'satker')->textInput() ?>

    <?= $form->field($model, 'no_sk')->textInput() ?>

    <?= $form->field($model, 'tmt_sk')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
