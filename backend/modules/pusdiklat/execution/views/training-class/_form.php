<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TrainingClass */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="training-class-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
    <?= $form->field($model, 'training_id')->textInput() ?>

    <?= $form->field($model, 'class')->textInput(['maxlength' => 5]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
