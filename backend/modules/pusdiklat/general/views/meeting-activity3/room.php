<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Dropdown;
use kartik\widgets\Select2;
use kartik\widgets\AlertBlock;

/* @var $searchModel backend\models\RoomSearch */

$this->title = $activity->name;
$this->params['breadcrumbs'][] = ['label' => 'Meetings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="room-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview-room',
	]); ?>
	<?php 
	if (Yii::$app->request->isAjax){	
		echo AlertBlock::widget([
			'useSessionFlash' => true,
			'type' => AlertBlock::TYPE_ALERT
		]); 
	}
	?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute' => 'code',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
			],            
			[
				'attribute' => 'name',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
			],
            
			[
				//'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'capacity',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				//'editableOptions'=>['header'=>'Capacity', 'size'=>'md','formOptions'=>['action'=>\yii\helpers\Url::to('editable')]]
			],
		
			[
				//'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'computer',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				//'editableOptions'=>['header'=>'Computer', 'size'=>'md','formOptions'=>['action'=>\yii\helpers\Url::to('editable')]]
			],
		
			[
				//'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'hostel',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				//'editableOptions'=>['header'=>'Hostel', 'size'=>'md','formOptions'=>['action'=>\yii\helpers\Url::to('editable')]]
			],
			[
				'format' => 'raw',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'label' => 'Availability',
				'width'=>'80px',
				'value' => function ($data) use ($activity) {
					$start = $activity->start;
					$finish = $activity->end;
					// ONLY CHECK AVAILABILITY
					$activityRoom = \backend\models\ActivityRoom::find()
								->where('
									room_id = :room_id 
									AND
									((start between :start AND :finish)
										OR (end between :start AND :finish))
									AND 
									status = :status
								',
								[
									':room_id' => $data->id,
									':start' => $start,
									':finish' => $finish,
									':status' => 2,
								]);
					// Available			
					if($activityRoom->count()==0){ 
						$activityRoom2 = \backend\models\ActivityRoom::find()
								->where('
									room_id = :room_id 
									AND
									activity_id = :activity_id
								',
								[
									':room_id' => $data->id,
									':activity_id' => $activity->id,
								]);
						if($activityRoom2->count()==0){ 		
							$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
							if($data->satker_id==$satker_id){
								return 
									Html::a('<span class="fa fa-check"></span>', ['set-room','activity_id'=>$activity->id,'room_id'=>$data->id,'status'=>2], ['class' => 'label label-success link-post','data-pjax'=>0,'title'=>'click to approve it!','data-toggle'=>"tooltip",'data-placement'=>"top",]).' '.
									Html::a('<span class="fa fa-times"></span>', ['set-room','activity_id'=>$activity->id,'room_id'=>$data->id,'status'=>3], ['class' => 'label label-danger link-post','data-pjax'=>0,'title'=>'click to rejected it!','data-toggle'=>"tooltip",'data-placement'=>"top",]);;
							}
							else{
								return 
									Html::a('<span class="fa fa-square-o"></span>', ['set-room','activity_id'=>$activity->id,'room_id'=>$data->id], ['class' => 'label label-info link-post','data-pjax'=>0,'title'=>'click to set it!','data-toggle'=>"tooltip",'data-placement'=>"top",]);
							}	
						}
						else{
							$modelActivityRoom2 = $activityRoom2->one();
							$status = $modelActivityRoom2->status;
							$text_status = "<span class='label label-warning'>Waiting</span>";
							if($status==1) $text_status = "<span class='label label-info'>Process</span>".Html::a('<span class="fa fa-times"></span>', ['unset-room','activity_id'=>$activity->id,'room_id'=>$data->id], ['class' => 'label label-danger link-post','data-pjax'=>0]);
							else if($status==2) $text_status = "<span class='label label-success'>Approved</span>";
							else if($status==3) $text_status = "<span class='label label-danger'>Rejected</span>";
							return $text_status;								
						}
					}
					else{
						$modelActivityRoom = $activityRoom->all();
						$same = 0;
						$status = 0;
						foreach($modelActivityRoom as $mAR){
							if($mAR->activity_id==$activity->id){
								$same=1;
								$status = $mAR->status;
								break;
							}
						}
						if($same==1){
							$text_status = "<span class='label label-warning'>Waiting</span>";
							if($status==1) $text_status = "<span class='label label-info'>Process</span>".Html::a('<span class="fa fa-times"></span>', ['unset','activity_id'=>$activity->id,'room_id'=>$data->id], ['class' => 'label label-danger link-post','data-pjax'=>0]);
							else if($status==2) $text_status = "<span class='label label-success'>Approved</span>";
							else if($status==3) $text_status = "<span class='label label-danger'>Rejected</span>";
							return $text_status;								
						}
						else{
							return "-";
						}
					}
				}
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Set Room</h3>',
			//'type'=>'primary',
			'before'=>
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'satker_id', 
					'data' => $satkers,
					'value' => $satker_id,
					'options' => [
						'width'=> '200px;',
						'placeholder' => 'Satker ...', 
						'class'=>'form-control', 
						'id'=>'select2-satker_id',
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['room','activity_id'=>$activity_id]).'&satker_id="+$(this).val(), 
								container: "#pjax-gridview-room", 
								timeout: 1000,
							});
						',	
					],
				]).
				'</div>',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', ['room','activity_id'=>$activity_id,'satker_id'=>$satker_id], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?php 
	$this->registerCss('.select2-container { width: 250px !important; }');
	if (Yii::$app->request->isAjax){
		$this->registerJs('
			//$("#select2-satker_id").select2().select2("val", '.($activity->location-1).');
			$("a.link-post").on("click", function () {
				var $link = $(this);
				$.ajax({
					type: "POST",
					cache: false,
					url: $link.prop("href"),
					//data: $link.serializeArray(),
					success: function (data) {		
						$.pjax.reload({
							url: "'.\yii\helpers\Url::to(['room','activity_id'=>$activity_id,'satker_id'=>$satker_id]).'", 
							container: "#pjax-gridview-room",
							timeout: 1000,
						});
						$.growl(data, {	type: "success"	});
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						alert(XMLHttpRequest.responseText);
					}
				});
				return false;
			});
			$("#modal-heart").on("hidden.bs.modal", function (e) {
				$.pjax.reload({
					url: "'.\yii\helpers\Url::to(['index','organisation_id'=>$activity->meeting->organisation_id]).'", 
					container: "#pjax-gridview",
					timeout: 1,
				});
			});
		');
	}
	?>
	<?php \yii\widgets\Pjax::end(); ?>

</div>
