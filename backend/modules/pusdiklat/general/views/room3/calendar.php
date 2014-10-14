<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Room */

$this->title = $room->name;
$this->params['breadcrumbs'][] = ['label' => 'Rooms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="room-view">
	
	<?= Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['activity-room','id'=>$room->id], ['class' => 'btn btn-warning']) ?>
	<hr>
    <?php
    use hscstudio\heart\widgets\FullCalendar; 
	use yii\helpers\Url;
	\yii\widgets\Pjax::begin([
		'id'=>'pjax-calendar',
	]);
	echo FullCalendar::widget([
		'options'=>[
			'id'=>'calendar',
			'header'=>[
				'left'=>'prevYear,prev,next,nextYear today',
				'center'=>'title',
				'right'=>'month,agendaWeek,agendaDay',
			],			
			'editable'=> true,
			'eventLimit'=>true, // allow "more" link when too many events
			'eventClick'=> new yii\web\JsExpression('function(calEvent, jsEvent, view){
				var $modal = $("#modal-heart");
				var $link = $(this);
				var $source = "";//.table-responsive";
				$modal.find(".modal-refresh").attr("href", $link.attr("href"));
				$modal.find(".modal-title").text("View");
				$modal.find(".modal-body .content").html("Loading ...");
				$modal.modal("show");				
				$.ajax({
					type: "POST",
					cache: false,
					url: $link.prop("href"),
					data: $(".form-heart form").serializeArray(),
					success: function (data) {		
						if ($source) 
							result = $(data).find($source);
						else
							result = data;
						$modal.find(".modal-body .content").html(result);
						$modal.find(".modal-body .content").css("max-height", ($(window).height() - 200) + "px");
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						$modal.find(".modal-body .content").html("<div class=\'error\'>" + XMLHttpRequest.responseText + "</div>");
					}
				});
				return false;
			}'),
			'events'=> Url::to(['event-activity-room','id'=>$room->id,'status'=>$status]),
			'eventRender'=> new yii\web\JsExpression('function(event, element) {
				element.attr("title", event.description);
				element.attr("class", element.attr("class")+" "+event.class);
				element.attr("modal-size", event.modalSize);
				element.attr("modal-title", event.modalTitle);
				element.attr("data-toggle", event.dataToggle);
			}')
		],
    ]);
	\yii\widgets\Pjax::end(); 
	?>
	<?= \hscstudio\heart\widgets\Modal::widget(['modalSize'=>'modal-lg']); ?>
</div>
