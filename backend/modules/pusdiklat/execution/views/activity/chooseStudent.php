<?php
use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\planning\models\TrainerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Choose Student');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', ''.Inflector::camel2words($model->training->activity->name)), 'url' => ['student','id'=>$model->id]];
/* $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trainer Recommendation : '.$program_subject->name), 'url' => ['subject-trainer','id' => $model->id, 'subject_id' => $program_subject->id]];  */
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trainer-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Trainer',
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
				'header' => '<div style="text-align:center">Name</div>',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					return Html::a($data->person->front_title.' '.$data->person->name.' '.$data->person->back_title,['./person/view','id'=>$data->person_id],
						['class'=>'modal-heart','title'=>$data->person->name,'source'=>'div.panel-body','data-toggle'=>'tooltip']);							
				}					
			],
			[
				'attribute' => 'phone',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					return Html::a($data->person->phone,['./person/view','id'=>$data->person_id],
						['class'=>'modal-heart badge','title'=>$data->person->phone,'source'=>'div.panel-body','data-toggle'=>'tooltip']);							
				}					
			],
			
			[
				'attribute' => 'nid',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'150px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					return Html::tag('span',$data->person->nid,['class'=>'label label-default']);							
				}					
			],
			
			[
				'attribute' => 'nip',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'150px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					return Html::tag('span',$data->person->nip,['class'=>'label label-default']);							
				}					
			],
            [
				'label' => 'Action',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data) use ($model){
					$trainingStudentCount = \backend\models\TrainingStudent::find()
						->where([
							'training_id' => $model->id,
							'student_id' => $data->person_id,
						])
						->count();
					if($trainingStudentCount>0){
						return Html::tag('span', '<i class="fa fa-fw fa-check-square-o"></i>', [
							'class'=>'label label-success',
							'title'=>'Peserta Diklat',
							'data-toggle'=>'tooltip',
						]);
					}
					$icon = '<span class="fa fa-fw fa-square-o"></span>';			
					return Html::a($icon, 
						['set-student','id'=>$model->id,'student_id'=>$data->person_id], 
						[						
							'class'=>'label label-warning modal-heart',
							'data-toggle'=>'tooltip',
							'data-pjax'=>'0',
							'data-html'=>'true',
							'title'=>'Daftarkan',
							'modal-title'=>'',
							'modal-size'=>'modal-lg'
						]);
				}, 
			],		
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-circle-left"></i> Back ', ['student','id'=>$model->id], ['class' => 'btn btn-warning']).' ',
			 'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	
	<?= \hscstudio\heart\widgets\Modal::widget() ?>

</div>

<!--
<div class="well">
	<p class="lead">
	Jika data peserta tidak ditemukan, maka Anda di perkenankan untuk memasukkan data peserta baru.
	</p>
	<p class="lead" style="text-align:center">
	<?php
	echo Html::a('<i class="fa fa-fw fa-plus"></i> Tambah Data Peserta Baru ', ['activity/create-person'], [
		'class' => 'btn btn-success',
		'data-confirm' => 'Apakah Anda yakin akan menambah data peserta baru! pastikan tidak terjadi duplikasi data!',
	]);
	?>
	</p>
</div>
-->