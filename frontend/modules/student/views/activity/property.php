<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model backend\models\Activity */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Property #'. Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-view  panel panel-default">
	
	<?php
	Box::begin([
		'type'=>'small', // ,small, solid, tiles
		'bgColor'=>'red', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
		'bodyOptions' => [],
		'icon' => 'glyphicon glyphicon-eye-open',
		'link' => ['dashboard','training_id'=>\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($model->id))],
		'footer' => '<i class="fa fa-arrow-circle-left"></i> Back',
	]);
	?>
	<h3>Property</h3>
	<p>Property of Training</p>
	<?php
	Box::end();
	?>
	<div class="panel-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#training" role="tab" data-toggle="tab">Training <span class="label label-primary">1</span> </a></li>
			<li><a href="#program" role="tab" data-toggle="tab">Program <span class="label label-warning">2</span> </a></li>
			<li><a href="#subject" role="tab" data-toggle="tab">Subject <span class="label label-success">3</span> </a></li>
			<li><a href="#document" role="tab" data-toggle="tab">Document <span class="label label-info">4</span> </a></li>
		</ul>
		<!-- Tab panes -->	
		<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:5px; background-color: #fff;">
			<div class="tab-pane fade-in active" id="training">
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
			<div class="tab-pane fade" id="program">
				<?= DetailView::widget([
				'model' => $program,
				'attributes' => [
		            'id',
					'number',
					'name',
					'hours',
					'days',
					'test',
					'note',
					'stage',
					'category',
					'validation_status',
					'validation_note',
					'status',
					'created',
					'created_by',
					'modified',
					'modified_by',
					],
				]) ?>
			</div>
			<div class="tab-pane fade" id="subject">
				<?= GridView::widget([
					'dataProvider' => $subject,
					//'filterModel' => $searchModel,
					'columns' => [
						['class' => 'kartik\grid\SerialColumn'],
						[
							'attribute' => 'type',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',$data->reference->name,[
									'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
								]);
							},
						],
						'name',
						[
							'attribute' => 'hours',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',$data->hours,[
									'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
								]);
							},
						],
						
						[
							'attribute' => 'test',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								if($data->test==1) {
									$icon='<span class="glyphicon glyphicon-check"></span>';
									return Html::a($icon,'#',['class'=>'label label-default','data-toggle'=>'tooltip','data-pjax'=>"0",'title'=>'Diklat dengan Ujian Akhir']);
								}
								else{
									$icon='<span class="glyphicon glyphicon-minus"></span>';
									return Html::a($icon,'#',['class'=>'badge','data-toggle'=>'tooltip','data-pjax'=>"0",'title'=>'Diklat tanpa Ujian Akhir']);
								}
							},
						],
						
						[
							'attribute' => 'sort',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',$data->sort,[
									'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
								]);
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
								$icon = ($data->status==1)?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';		
								return Html::tag('span', $icon, [
									'class'=>($data->status==1)?'label label-info':'label label-warning',
									'title'=>'Current status is '.(($data->status==1)?'publish':'draft'),
									'data-toggle'=>'tooltip',
								]);
							},
						],
						
					],
					'panel' => [
						'before'=>'',							
						'after'=>'',
						'showFooter'=>false
					],
					'responsive'=>true,
					'hover'=>true,
				]); ?>
			</div>
			<div class="tab-pane fade" id="document">
				<?= GridView::widget([
					'dataProvider' => $document,
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
						
					],
					'panel' => [
						'before'=>'',							
						'after'=>'',
						'showFooter'=>false
					],
					'responsive'=>true,
					'hover'=>true,
				]); ?>
			</div>
		</div>

			
	</div>
</div>
