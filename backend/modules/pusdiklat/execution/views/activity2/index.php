<?php
use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\planning\models\ActivitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Training Activities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Activity',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
-->
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview',
	]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],        
			[
				'attribute' => 'name',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format'=>'raw',
				'value' => function ($data){
					return Html::a($data->name,'#',[
						'title'=>$data->description.'<hr>'.$data->training->note,
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
				'label' => 'Student',
				'vAlign'=>'left',
				'hAlign'=>'center',
				'width' => '75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::a($data->training->student_count_plan,['index-student-plan'],
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
					// CEK AUTHORISE ACCESS
					$permit = \hscstudio\heart\helpers\Heart::OrganisationAuthorized(
						[
							'1213030100', // CEK KD_UNIT_ORG 1213030100 IN TABLE ORGANISATION IS SUBBIDANG PENYEL I
							'1213030000', // BIDANG PENYEL
							'1213000000', // PUSDIKLAT
						],
						[
							1, // 1= HEAD OF KD_UNIT_ORG
						]
					);
					$object_person=\backend\models\ObjectPerson::find()
						->where([
							'object'=>'activity',
							'object_id'=>$data->id,														
							'type'=>'organisation_1213030100' //1213030100 CEK KD_UNIT_ORG 1213030100 IN TABLE ORGANISATION IS SUBBIDANG PENYEL I
						])
						->one();
					if($permit){
						$options = [
									'class'=>'label label-info modal-heart',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>($object_person!=null)?'CURRENT PIC TRAINING <br> '.$object_person->person->name.'.<br> CLICK HERE TO CHANGE PIC':'CLICK HERE TO SET PIC',
									'modal-title'=>($object_person!=null)?'CHANGE PIC':'SET PIC',
									'modal-size'=>'modal-md',
								];
						$person_name = ($object_person!=null)?substr($object_person->person->name,0,5).'.':'-';
						return Html::a($person_name,['pic','id'=>$data->id],$options);
					}
					else{
						$options = [
									'class'=>'label label-info',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>($object_person!=null)?'CURRENT PIC TRAINING <br> '.$object_person->person->name.'':'PIC IS UNAVAILABLE',
								];
						$person_name = ($object_person!=null)?substr($object_person->person->name,0,5).'.':'-';
						return Html::tag('span',$person_name,$options);
					}
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
			
			// 'location',
            // 'hostel',
            // 'status',
            // 'created',
            // 'created_by',
            // 'modified',
            // 'modified_by',

            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{dashboard} {update}',
				'buttons' => [
					'dashboard' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-dashboard"></span>';
								return ($model->status!=2 AND $model->status!=1)?'':Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs',
									'data-pjax'=>'0',
								]);
							},
					'update' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-pencil"></span>';
								return ($model->status!=2 AND $model->status!=1)?'':Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs',
									'data-pjax'=>'0',
								]);
							},
				],		
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'year', 
					'data' => $year_training,
					'value' => $year,
					'options' => [
						'placeholder' => 'Year ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?status='.$status.'&year="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>'.
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['nocancel'=>'All -Cancel','all'=>'All','0'=>'Plan','1'=>'Ready','2'=>'Execute','3'=>'Cancel'],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?year='.$year.'&status="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1000,
							});
						',	
					],
				]).
				'</div>',
			'after'=>
				Html::a('<i class="fa fa-fw fa-link"></i> Student Plan', ['index-student-plan'], ['class' => 'btn btn-default','data-pjax'=>'0']).' '.
				Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
	<?php /* $this->registerCss('.select2-container{width:125px !important;}'); */ ?>
	<?php \yii\widgets\Pjax::end(); ?>
</div>
