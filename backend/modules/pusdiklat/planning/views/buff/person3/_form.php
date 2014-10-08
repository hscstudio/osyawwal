<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
    <?= $form->field($model, 'nip')->textInput(['maxlength' => 25]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'nickname')->textInput(['maxlength' => 25]) ?>

    <?= $form->field($model, 'front_title')->textInput(['maxlength' => 25]) ?>

    <?= $form->field($model, 'back_title')->textInput(['maxlength' => 25]) ?>

    <?= $form->field($model, 'nid')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'npwp')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'born')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'birthday')->textInput() ?>

    <?= $form->field($model, 'gender')->textInput() ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'homepage')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'office_phone')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'office_fax')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'office_email')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'office_address')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'bank_account')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'married')->textInput() ?>

    <?= $form->field($model, 'blood')->textInput(['maxlength' => 25]) ?>

    <?= $form->field($model, 'graduate_desc')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <?= $form->field($model, 'position_desc')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'organisation')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
