<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use backend\models\Reference;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\planning\models\TrainerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Set Trainer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
/* $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subject : '.Inflector::camel2words($model->training->activity->name)), 'url' => ['subject','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trainer Recommendation : '.$program_subject->name), 'url' => ['subject-trainer','id' => $model->training_id, 'subject_id' => $model->program_subject_id]]; */
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trainer-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($recommendation) ?> <!-- ADDED HERE -->
	<div class="jumbotron">
		<h1>Apakah Anda yakin</h1>
		<p class="lead">"<strong><?= $trainer->person->name; ?></strong>" akan direkomendasikan sebagai </p>
		<?php
		$data = ArrayHelper::map(Reference::find()
			->select(['id', 'name'])
			->where(['type'=>'trainer_type'])
			->asArray()
			->all()
			, 'id', 'name');
		echo $form->field($recommendation, 'type')->widget(Select2::classname(), [
			'data' => $data,
			'options' => [
				'placeholder' => 'Choose type ...',
				'style'=>'width:300px;',
			],
			'pluginOptions' => [
				'allowClear' => true,
			],
		])->label(false);
		?>  		
		<p>
		<?= Html::submitButton(Yii::t('app', 'Ya saya yakin!'), ['class' => 'btn btn-success btn-md']) ?>
		</p>
	</div> 
    <?php ActiveForm::end(); ?>

</div>