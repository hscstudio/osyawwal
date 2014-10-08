<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use backend\models\Reference;
use yii\helpers\Inflector;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\planning\models\TrainerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Set Student');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', ''.Inflector::camel2words($model->training->activity->name)), 'url' => ['student','id'=>$model->id]];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trainer-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($training_student) ?> <!-- ADDED HERE -->
	<div class="jumbotron">
		<h1>Apakah Anda yakin</h1>
		<p class="lead">"<strong><?= $student->person->name; ?></strong>" akan didaftarkan sebagai peserta </p>  		
		<p>
		<?= Html::submitButton(Yii::t('app', 'Ya saya yakin!'), ['class' => 'btn btn-success btn-md']) ?>
		</p>
	</div> 
    <?php ActiveForm::end(); ?>

</div>