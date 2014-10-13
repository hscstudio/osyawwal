<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model backend\models\Program */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Document {modelClass}: ', [
    'modelClass' => 'Program',
]) . ' ' . Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Document');
?>
<div class="program-update">
	<div class="panel-body">
		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute' => 'type',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->type,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'label' => 'Document Download',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function($data){
					return Html::a(
						''.$data->file->name,
						Url::to(['/file/download','file'=>$data->object.'/'.$data->object_id.'/'.$data->file->file_name]),
						[
							'class'=>'label label-default',
							'data-pjax'=>'0',
						]
					);
				},
			],
			[
				'label' => 'Upload Time',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function($data){
					return $data->file->created;
				},
			],
			[
				'label' => 'Status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
						
					$icon = ($data->file->status==1)?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
					
					return Html::tag('span', $icon, [
						'class'=>($data->file->status==1)?'label label-info':'label label-warning',
						'title'=>'Current status is '.(($data->file->status==1)?'publish':'draft'),
						'data-toggle'=>'tooltip',
					]);
					
				},
			],
		],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>	
	</div>
</div>
