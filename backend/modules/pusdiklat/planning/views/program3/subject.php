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
use backend\models\Reference;
/* @var $this yii\web\View */
/* @var $model backend\models\Program */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Subject {modelClass}: ', [
    'modelClass' => 'Program',
]) . ' ' . Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Subject');
?>
<div class="program-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<div id="div_form">
		<?php $form = ActiveForm::begin([
		]); ?>
			<div class="row clearfix">
				<div class="col-md-8">
				<?php		
				$data = ArrayHelper::map(Reference::find()
						->select(['id', 'name'])
						->where(['type'=>'stage'])
						->asArray()
						->all()
						, 'name', 'name');
				
				$program_subject->stage = explode('|',$program_subject->stage); 
				echo $form->field($program_subject, 'stage')->widget(Select2::classname(), [
					'data' => $data,
					'options' => [
						'placeholder' => 'Choose stage ...',
						'multiple' => true,
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				]);
				?>
				</div>
			</div>
			<div class="row clearfix">
				<div class="col-md-2">
					<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
				</div>
			</div>
		<?php ActiveForm::end(); ?>
		</div>
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
			[
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{update}',
				'buttons' => [
					'update' => function ($url, $data) {
						// CEK AUTHORISE ACCESS
						$permit = \hscstudio\heart\helpers\Heart::OrganisationAuthorized(
							[
								'1213020300', // CEK KD_UNIT_ORG 1213020300 IN TABLE ORGANISATION IS SUBBIDANG TP
								'1213020000', // BIDANG RENBANG
								'1213000000', // PUSDIKLAT
							],
							[
								1, // 1= HEAD OF KD_UNIT_ORG
							]							
						);
						if($permit){
							$icon='<span class="fa fa-fw fa-pencil"></span>';
							return Html::a($icon,['subject','id'=>$data->program_id,'action'=>'update','subject_id'=>$data->id],[
								'class'=>'btn btn-default btn-xs',
								'data-pjax'=>'0',
								'title'=>'Click to update'
							]);
						}
						else{
							return '';
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
								url: "'.Url::to(['subject','id'=>$model->id]).'&status="+$(this).val(), 
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
	<?php 
	$this->registerCss('label{display:block !important;}'); 	
	if (!in_array(Yii::$app->request->get('action'),['update'])){
		$this->registerJs('
			$("#div_form").slideToggle("slow");
		');
	} 	
	?>
	</div>
</div>
