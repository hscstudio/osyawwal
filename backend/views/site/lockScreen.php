<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Lock Screen';
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
					<?php $form = ActiveForm::begin([
						'id' => 'login-form',
						'action' => ['site/login','previous'=>$previous],
					]); ?>
						<?php						
						echo Html::activeHiddenInput($model, 'username');
						?>
						<h1><?= $model->username ?></h1>
						<?= $form->field($model, 'password')->passwordInput() ?>
						<?php
						$this->registerJs("
							$('#loginform-password').focus();
						");
						?>
						<?= $form->field($model, 'rememberMe')->checkbox() ?>
						<div class="form-group">
							<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
						</div>
						<div style="color:#999;margin:1em 0">
							Logged as someone else ? <?= Html::a('click here', ['site/login']) ?>.
						</div>						
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
