<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\pusdiklat\execution\models\TrainingClassSubjectSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="training-class-subject-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'training_class_id') ?>

    <?= $form->field($model, 'program_subject_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'modified') ?>

    <?php // echo $form->field($model, 'modified_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
