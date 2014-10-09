<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'People');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Person',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
		
			[
				'attribute' => 'name',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],
            
			[
				'attribute' => 'nid',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],
			
			[
				'header' => '<i class="fa fa-fw fa-user"></i>',
				'format'=>'raw',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'value' => function ($data){
					if(!empty($data->employee->person_id)){
						return Html::a($data->employee->person->name,['./employee/view','id'=>$data->employee->person_id],
								['class'=>'modal-heart badge','title'=>$data->employee->person->name,'source'=>'div.panel-body','data-toggle'=>'tooltip']);
					}
					else{
						return Html::a('<i class="fa fa-fw fa-plus"></i>',['./employee/create','person_id'=>$data->id],
								['class'=>'badge','title'=>'Create Employee Base On Person','data-toggle'=>'tooltip']);
					}					
					
				}
			],
            // 'nid',
            // 'npwp',
            // 'born',
            // 'birthday',
            // 'gender',
            // 'phone',
            // 'email:email',
            // 'homepage',
            // 'address',
            // 'office_phone',
            // 'office_fax',
            // 'office_email:email',
            // 'office_address',
            // 'bank_account',
            // 'married',
            // 'photo',
            // 'blood',
            // 'graduate',
            // 'graduate_desc',
            // 'position',
            // 'position_desc',
            // 'organisation',
            // 'status',
            // 'created',
            // 'created_by',
            // 'modified',
            // 'modified_by',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget(['modalSize'=>'modal-lg']) ?>
</div>
