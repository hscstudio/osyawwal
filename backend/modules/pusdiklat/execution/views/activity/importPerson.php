<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\execution\models\TrainingClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Student Import #'. Inflector::camel2words($model->name);
/* $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; */

?>
<div class="training-class-index">
	<?php	
	echo Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', \yii\helpers\Url::to(['student','id'=>$model->id]), ['class' => 'btn btn-warning']);
	$form = ActiveForm::begin();
	?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'pjax' => true, // pjax is set to always true for this demo
        /* 'filterModel' => $searchModel, */
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'=>'name',
				'format' => 'raw',
				'value' => function ($data) {
					return 
						Html::input('text','name[]',$data['name'],['class'=>'form-control']).
						Html::input('hidden','trainingStudent[]',$data['trainingStudent'],['class'=>'form-control']).
						Html::input('hidden','student[]',$data['student'],['class'=>'form-control']).
						Html::input('hidden','person[]',$data['person'],['class'=>'form-control']);
				}
			],
			[
				'attribute'=>'nip',
				'format' => 'raw',
				'value' => function ($data){
					return Html::input('text','nip[]',$data['nip'],['class'=>'form-control']);
				}
			],
			[
				'attribute'=>'unit',
				'format' => 'raw',
				'value' => function ($data){
					return Html::input('text','unit[]',$data['unit'],['class'=>'form-control']);
				}
			],
			[
				'class' => 'kartik\grid\CheckboxColumn',
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i></h3>',
			'after'=>Html::submitButton('Submit', ['class'=>'btn btn-primary']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?php
	ActiveForm::end();
	?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
</div>
<?php
$this->registerJs(
	'
	var idx =  0;
	var exists = ['.implode(',',$exists).'];
	$("input[name=\'selection[]\']").each(function() {
		if (exists.indexOf(idx)==-1){
			$(this).prop( "checked", true );
		}
		else{
			$(this).prop( "checked", false );			
		}		
		idx++;
	});
	'
);
?>