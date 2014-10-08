<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
/*
$this->params['breadcrumbs'][] = $this->title;
 */
?>
<div class="site-login" style="width:350px; margin:auto;">
	<div class="panel  panel-default">
		<div class="panel-heading"><h1 class="panel-title"><?= Html::encode($this->title) ?></h1></div>
		<div class="panel-body">
			<p>Please fill out the following fields to login:</p>

			<div class="row">
				<div class="col-lg-12">
					<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
						<?= $form->field($model, 'username') ?>
						<?= $form->field($model, 'password')->passwordInput() ?>
						<?= $form->field($model, 'rememberMe')->checkbox() ?>
						<div class="form-group">
							<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
						</div>
						<div style="color:#999;margin:1em 0">
							If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
						</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
