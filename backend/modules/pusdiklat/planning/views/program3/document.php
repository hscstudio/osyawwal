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
<div class="program-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<?php $form = ActiveForm::begin([
			'options'=>['enctype'=>'multipart/form-data']
		]); ?>
			<div class="row">
				<div class="col-md-2">
				<?php
				$data = ['kap'=>'KAP','gbpp'=>'GBPP','module'=>'MODUL'];
				echo $form->field($object_file, 'type')->widget(Select2::classname(), [
					'data' => $data,
					'options' => [
						'placeholder' => 'Choose type ...',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				]);
				?>
				</div>
				<div class="col-md-3">
				<?php
				echo $form->field($file, 'name');
				?>
				</div>
				<div class="col-md-4">
				<?php 
				echo $form->field($file, 'file_name')->widget(FileInput::classname(), [
					'pluginOptions' => [
						'showUpload' => false,
					]
				])->label(); 
				?>
				</div>
				<div class="col-md-3">
					<label class="control-label" style="display:block">&nbsp;</label>
					<?= Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-success']) ?>
				</div>
			</div>
		<?php ActiveForm::end(); ?>
		<hr>
		<?php \yii\widgets\Pjax::begin([
			'id'=>'pjax-gridview',
		]); ?>
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
					// CEK AUTHORISE ACCESS
					$permit = \hscstudio\heart\helpers\Heart::OrganisationAuthorized(
						[
							'1213020200', // CEK KD_UNIT_ORG 1213020200 IN TABLE ORGANISATION IS SUBBIDANG KURIKULUM
							'1213020000', // BIDANG RENBANG
							'1213000000', // PUSDIKLAT
						],
						[
							1, // 1= HEAD OF KD_UNIT_ORG
						]
					);
						
					$icon = ($data->file->status==1)?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
					if($permit){
						return Html::a($icon, ['document-status','id'=>$data->object_id,'type'=>$data->type,'file_id'=>$data->file_id,'status'=>$data->file->status], [
							'onclick'=>'
								$.pjax.reload({url: "'.\yii\helpers\Url::to(['document','id'=>$data->object_id, 'status'=>'all']).'", container: "#pjax-gridview", timeout: 3000});
								return false;
							',
							'class'=>($data->file->status==1)?'label label-info':'label label-warning',
							'title'=>'Current status is '.(($data->file->status==1)?'publish':'draft'),
							'data-toggle'=>'tooltip',
						]);
					}
					else{
						return Html::tag('span', $icon, [
							'class'=>($data->file->status==1)?'label label-info':'label label-warning',
							'title'=>'Current status is '.(($data->file->status==1)?'publish':'draft'),
							'data-toggle'=>'tooltip',
						]);
					}
				},
			],
			[
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{delete}',
				'buttons' => [
					'delete' => function ($url, $data) {
						// CEK AUTHORISE ACCESS
						$permit = \hscstudio\heart\helpers\Heart::OrganisationAuthorized(
							[
								'1213020200', // CEK KD_UNIT_ORG 1213020200 IN TABLE ORGANISATION IS SUBBIDANG KURIKULUM
								'1213020000', // BIDANG RENBANG
								'1213000000', // PUSDIKLAT
							],
							[
								1, // 1= HEAD OF KD_UNIT_ORG
							]							
						);
						if($permit){
							$icon='<span class="fa fa-fw fa-trash"></span>';
							return ($data->file->status==1)?'-':Html::a($icon,['document-delete','id'=>$data->object_id,'type'=>$data->type,'file_id'=>$data->file_id],[
								'data-method'=>'post',
								'class'=>'btn btn-default btn-xs',
								'data-pjax'=>'0',
								'data-confirm'=>"Are you sure to delete this item?",
								'title'=>'Click to delete'
							]);
						}
						else{
							return '-';
						}
					},
				],
			],
		],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>	
				Html::a('<i class="fa fa-fw fa-arrow-circle-left"></i> Back', Url::to(['index']), ['class' => 'btn btn-warning','data-pjax'=>'0']).' '.
				'<div class="pull-right" style="margin-right:5px;width:125px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['1'=>'Published','0'=>'Unpublished','all'=>'All'],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.Url::to(['document','id'=>$model->id]).'&status="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>	
	<?php \yii\widgets\Pjax::end(); ?>	
	</div>
</div>
