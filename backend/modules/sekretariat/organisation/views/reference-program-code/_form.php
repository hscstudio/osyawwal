<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Reference */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reference-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->

    <?php
	$data = ArrayHelper::map(
		\backend\models\Reference::find()
			->select(['id','name'])
			->where(['type'=>'program_code'])
			->orderBy(['[[id]]'=> SORT_ASC])			
			->asArray()
			->all(), 
			'id', 'name'
	);
	
	echo $form->field($model, 'parent_id')->widget(Select2::classname(), [
		'data' => array_merge([0=>'---'],$data),
		'options' => ['placeholder' => 'Choose Parent ...'],
		'pluginOptions' => [
			'allowClear' => true
		],
	]);
	?>

    <?php //$form->field($model, 'type')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

   	<?= $form->field($model, 'status')->widget(\kartik\widgets\SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'On',
						'offText' => 'Off',
					]
				]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
