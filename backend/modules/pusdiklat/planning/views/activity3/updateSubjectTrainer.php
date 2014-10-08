<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
/* @var $this yii\web\View */
/* @var $model backend\models\TrainingSubjectTrainerRecommendation */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Update #'.$model->trainer->person->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subject : '.Inflector::camel2words($model->training->activity->name)), 'url' => ['subject','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trainer Recommendation : '.$program_subject->name), 'url' => ['subject-trainer','id' => $model->training_id, 'subject_id' => $model->program_subject_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-subject-trainer-recommendation-update panel panel-default">	
   <?php 
	if (!Yii::$app->request->isAjax){ ?>
    <div class="panel-heading"> 
		<div class="pull-right">
        <?= Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['subject-trainer','id' => $model->training_id, 'subject_id' => $model->program_subject_id], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<?php } ?>
	<div class="panel-body">
		<?= $this->render('_form_subject_trainer', [
			'model' => $model,
		]) ?>
	</div>
</div>
