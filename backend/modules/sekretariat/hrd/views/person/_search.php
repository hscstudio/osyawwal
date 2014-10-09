<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PersonSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nip') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'nickname') ?>

    <?= $form->field($model, 'front_title') ?>

    <?php // echo $form->field($model, 'back_title') ?>

    <?php // echo $form->field($model, 'nid') ?>

    <?php // echo $form->field($model, 'npwp') ?>

    <?php // echo $form->field($model, 'born') ?>

    <?php // echo $form->field($model, 'birthday') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'homepage') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'office_phone') ?>

    <?php // echo $form->field($model, 'office_fax') ?>

    <?php // echo $form->field($model, 'office_email') ?>

    <?php // echo $form->field($model, 'office_address') ?>

    <?php // echo $form->field($model, 'bank_account') ?>

    <?php // echo $form->field($model, 'married') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'blood') ?>

    <?php // echo $form->field($model, 'graduate') ?>

    <?php // echo $form->field($model, 'graduate_desc') ?>

    <?php // echo $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'position_desc') ?>

    <?php // echo $form->field($model, 'organisation') ?>

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
