<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'User',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            
			[
				'attribute' => 'username',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],
            
			[
				'label' => 'Employee',
				'format' => 'raw',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){
					$employee = \backend\models\Employee::find()
						->where([
							'user_id'=>$data->id,							
						])
						->one();
					if(null!=$employee){
						return Html::a($employee->person->name,['./employee2/view','id'=>$employee->person_id],
							['class'=>'modal-heart badge','title'=>$employee->person->name,'source'=>'div.panel-body','data-toggle'=>'tooltip']);
					}							
				}
			],
			
            // 'password_hash',
            // 'password_reset_token',
            'email:email',       
            
			[
				'attribute' => 'status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width' => '100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format' => 'raw',
				'value' => function ($data){
					return Html::tag('span',($data->status==1)?'Active':'Blocked',['class'=>($data->status==1)?'label label-info':'label label-warning']);
				}
			],
			[
				'label' => 'Block',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width' => '100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format' => 'raw',
				'value' => function ($data){
					if($data->status==1){
						return Html::a('block',['block','id'=>$data->id],[
							'data-method'=>'post',
							'data-confirm'=>'Do You sure block this user?',
							'class'=>'label label-danger',
							'title'=>'Click to block',
							'data-toggle'=>'tooltip',
						]);
					}
					else{
						return Html::a('unblock',['unblock','id'=>$data->id],[
							'data-method'=>'post',
							'data-confirm'=>'Do You sure unblock this user?',
							'class'=>'label label-success',
							'title'=>'Click to unblock',
							'data-toggle'=>'tooltip',
						]);
					}
				}
			],
            // 'created_at',
            // 'updated_at',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>' ',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget(['modalSize'=>'']) ?>
</div>
