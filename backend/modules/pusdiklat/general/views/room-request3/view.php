<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Activity */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Update #'. Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-view  panel panel-default">

   <div class="panel-heading"> 
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">

		<!--
		<p>
			<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
					'method' => 'post',
				],
			]) ?>
		</p>
		-->
		<ul class="nav nav-tabs" role="tablist" id="tab_wizard">
			<li class="active"><a href="#property" role="tab" data-toggle="tab">Property <span class='label label-info'>1</span></a></li>
			<li class=""><a href="#history" role="tab" data-toggle="tab">History <span class='label label-warning'>2</span></a></li>
		</ul>
		<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:10px; background-color: #fff;">
			<div class="tab-pane fade-in active" id="property">
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'id',
				'satker_id',
				'name',
				'description:ntext',
				'start',
				'end',
				'location',
				'hostel',
				'status',
				'created',
				'created_by',
				'modified',
				'modified_by',
					],
				]) ?>
			</div>
			<div class="tab-pane fade" id="history">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					'columns' => [
						'revision',
						[
							'attribute' => 'name',
							'vAlign'=>'middle',
							'hAlign'=>'left',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],	
							'format'=>'raw',
							'value' => function ($data){
								return Html::a($data->name,'#',[
									'title'=>$data->description.
									'<br>'.$data->location.
									'<br>'.$data->hostel,
									'data-toggle'=>"tooltip",
									'data-placement'=>"top",
									'data-html'=>'true',
								]);
							},
						],            
						[
							'attribute' => 'start',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'width'=>'100px',
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',date('d M Y',strtotime($data->start)),[
									'class'=>'label label-info',
								]);
							},
						],
					
						[
							'attribute' => 'end',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'width'=>'100px',
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',date('d M Y',strtotime($data->end)),[
									'class'=>'label label-info',
								]);
							},
						],
						[
							'label' => 'Attendance',
							'vAlign'=>'left',
							'hAlign'=>'center',
							'width' => '75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								return Html::a($data->meeting->attendance_count_plan,'#',
									[
										'class'=>'label label-primary',
										'data-pjax'=>'0',
										'data-toggle'=>'tooltip',
										'title' => 'Click to view student spread plan',
									]);
							},
						],
						
						[
							'format' => 'raw',
							'label' => 'PIC',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'70px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'value' => function ($data){
								$object_person=\backend\models\ObjectPerson::find()
									->where([
										'object'=>'activity',
										'object_id'=>$data->id,
										'type'=>'organisation_1213020100', // CEK KD_UNIT_ORG 1213020100 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
									])
									->one();
								
								$options = [
											'class'=>'label label-info',
											'data-toggle'=>'tooltip',
											'data-pjax'=>'0',
											'data-html'=>'true',
											'title'=>($object_person!=null)?'CURRENT PIC PROGRAM <br> '.$object_person->person->name.'':'PIC IS UNAVAILABLE',
										];
								$person_name = ($object_person!=null)?substr($object_person->person->name,0,5).'.':'-';
								return Html::tag('span',$person_name,$options);
							}
						],
						
						[
							'attribute' => 'status',
							'filter' => false,
							'label' => 'Status',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								
								$status_icons = [
									'0'=>'<span class="glyphicon glyphicon-fire"></span>',
									'1'=>'<span class="glyphicon glyphicon-refresh"></span>',
									'2'=>'<span class="glyphicon glyphicon-check"></span>',
									'3'=>'<span class="glyphicon glyphicon-remove"></span>'
								];
								$status_classes = ['0'=>'warning','1'=>'info','2'=>'success','3'=>'danger'];
								$status_title = ['0'=>'Plan','1'=>'Ready','2'=>'Execution','3'=>'Cancel'];
								return Html::tag(
									'span',
									$status_icons[$data->status],
									[
										'class'=>'label label-'.$status_classes[$data->status],
										'data-toggle'=>'tooltip',
										'data-pjax'=>'0',
										'title'=>$status_title[$data->status],
									]
								);
							},
						],
						[
							'attribute' => 'modified',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'width'=>'140px',
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',date('d M Y H:i:s',strtotime($data->modified)),[
									'class'=>'label label-info',
								]);
							},
						],
					]
				])
				?>
			</div>
		</div>
	</div>
</div>
