<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\pusdiklat\planning\models\ActivitySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activity-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'satker_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'start') ?>

    <?php // echo $form->field($model, 'end') ?>

    <?php // echo $form->field($model, 'location') ?>

    <?php // echo $form->field($model, 'hostel') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'modified') ?>

    <?php // echo $form->field($model, 'modified_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
