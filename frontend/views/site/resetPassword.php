<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

$this->title = 'Reset password';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password" style="width:350px; margin:auto;">
    <div class="panel  panel-default">
		<div class="panel-heading"><h1 class="panel-title"><?= Html::encode($this->title) ?></h1></div>
		<div class="panel-body">

			<p>Please choose your new password:</p>

			<div class="row">
				<div class="col-lg-12">
					<?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
						<?= $form->field($model, 'password')->passwordInput() ?>
						<div class="form-group">
							<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>

