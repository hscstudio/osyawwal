<?php

namespace backend\modules\pusdiklat\general\controllers;

use Yii;
use backend\models\Room;
use backend\modules\pusdiklat\general\models\RoomSearch;
use backend\models\Activity;
use backend\models\ActivityRoom;
use backend\modules\pusdiklat\general\models\ActivityRoomSearch;
use backend\models\Reference;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Room3Controller implements the CRUD actions for Room model.
 */
class Room3Controller extends Controller
{
    public $layout = '@hscstudio/heart/views/layouts/column2';
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Room models.
     * @return mixed
     */
    public function actionIndex($status=1,$satker_id=0)
    {
        $searchModel = new RoomSearch();
		if($satker_id==0)
			$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
			
		if($status=='all'){
			if($satker_id=='all'){
				$queryParams['RoomSearch']=[];
			}
			else{
				$queryParams['RoomSearch']=[
					'satker_id'=>$satker_id,
				];
			}
		}
		else{
			if($satker_id=='all'){
				$queryParams['RoomSearch']=[
					'status'=>$status,
				];
			}
			else{
				$queryParams['RoomSearch']=[
					'satker_id'=>$satker_id,
					'status'=>$status,
				];
			}
		}
        $queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);

        $references = Reference::find()			
			->where([
				'type'=>'satker'
			])
			->all();;
		$satkers = [];
		foreach ($references as $reference){
			$countWaiting = 0;								
			$countWaiting1 = ActivityRoom::find()
							->joinWith('activity')
							->where([
								'activity_room.status' => [0,1],
								'room_id' => Room::find()
									->where([
										'satker_id' => $reference->id,
										'status' => 1,
									])
									->column()
							])
							->andWhere('satker_id='.Yii::$app->user->identity->employee->satker_id)
							->count();
			$countWaiting2 = ActivityRoom::find()
							->joinWith('activity')
							->where([
								'activity_room.status' => 1,
								'room_id' => Room::find()
									->where([
										'satker_id' => $reference->id,
										'status' => 1,
									])
									->column()
							])
							->andWhere('satker_id!='.Yii::$app->user->identity->employee->satker_id)
							->count();
			$countWaiting = $countWaiting1 + $countWaiting2;
			
			$waiting = '';
			if ($countWaiting>0) $waiting = '['.$countWaiting.']';
			$satkers[$reference->id] = $reference->name. ' '.$waiting;
		}
		$satkers['all']='-- All --';
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'status' => $status,
			'satker_id'=>$satker_id,
			'satkers'=>$satkers,
        ]);
    }

    /**
     * Displays a single Room model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Room model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Room([
			'status' => 1,
			'capacity' => 30,
			'owner' => 1,
		]);

        if ($model->load(Yii::$app->request->post())){ 
			$model->satker_id = (int)Yii::$app->user->identity->employee->satker_id;
			if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
			}
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Room model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'Data have updated.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
			}
			return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
				
            ]);
        }
    }

    /**
     * Deletes an existing Room model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if($this->findModel($id)->delete()) {
			Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
		}
		else{
			Yii::$app->getSession()->setFlash('error', 'Data is not deleted.');
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the Room model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Room the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		if (($model = Room::find()
				->where([
					'id' => $id,
					'satker_id' => $satker_id
				])
				->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
     * Lists all ActivityRoom models.
     * @return mixed
     */
    public function actionActivityRoom($id,$status='all')
    {
        $model = Room::find()
			->where([
				'id'=>$id,	
			])
			->one();
		
		$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		$searchModel = new ActivityRoomSearch();
        $queryParams = Yii::$app->request->getQueryParams();
		$params = [];
		if($status=='all') 
			$params = ['room_id' => $id];
		else 
			$params = ['room_id' => $id,'status' => $status];
		$queryParams['ActivityRoomSearch']=$params;
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_DESC,'end'=>SORT_DESC];
		
		
        return $this->render('activityRoom', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
			'status' => $status,
        ]);
    }
	
	/**
     * Updates an existing ActivityRoom model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateActivityRoom($id,$activity_id)
    {
		$room = Room::find()
			->where([
				'id'=>$id,				
			])
			->one();
		$model = ActivityRoom::find()->where([
			'activity_id'=>$activity_id,
			'room_id'=>$id
		])->one();        
		//CHECKING ACTIVITY ID OF CURRENT SATKER
		$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		$activity = Activity::findOne($model->activity_id);
		if($activity->satker_id!=$satker_id and $room->satker_id!=$satker_id){
			Yii::$app->session->setFlash('error', 'You have not privileges to update this activity!');
			return $this->redirect(['activity-room','id'=>$id]);
		}
		
		$data_status = [
			'0'=>'Waiting',
			'1'=>'Process',
			'2'=>'Approved',
			'3'=>'Rejected',
		];
		if($satker_id!=$room->satker_id){
			$data_status = [
				'0'=>'Waiting',
				'1'=>'Process',
			];
		}		
		if($satker_id!=$activity->satker_id){
			$data_status = [
				'1'=>'Process',
				'2'=>'Approved',
				'3'=>'Rejected',
			];
		}
		
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
				Yii::$app->session->setFlash('success', 'Data saved');				
				
				if (Yii::$app->request->isAjax){

				}
				else{					
					if(Yii::$app->request->post()['redirect']=='calendar'){
						return $this->redirect(['calendar-activity-room','id'=>$id]);
					}
					else{
						return $this->redirect(['activity-room','id'=>$id]);
					}
				}
            } else {
                // error in saving model
				Yii::$app->session->setFlash('error', 'There are some errors');
				if(Yii::$app->request->post()['redirect']=='calendar'){
					return $this->redirect(['calendar-activity-room','id'=>$id]);
				}
				if (Yii::$app->request->isAjax){		
				
				}
				else{					
					if(Yii::$app->request->post()['redirect']=='calendar'){
						return $this->redirect(['calendar-activity-room','id'=>$id]);
					}
					else{
						return $this->redirect(['activity-room','id'=>$id]);
					}
				}
            }            
        }
		else{
			//return $this->render(['update', 'id' => $model->id]);
			if (Yii::$app->request->isAjax){	
				return $this->renderAjax('updateActivityRoom', [
					'model' => $model,
					'id'=>$id,
					'activity_id'=>$activity_id,
					'data_status' => $data_status,
				]);
			}
			else{
				return $this->render('updateActivityRoom', [
					'model' => $model,
					'id'=>$id,
					'activity_id'=>$activity_id,
					'data_status' => $data_status,
				]);
			}
		}
    }
	
	/**
     * Lists all ActivityRoom models.
     * @return mixed
     */
    public function actionCalendarActivityRoom($id,$status='all')
    {
        /*
		$ref_satker_id = (int)Yii::$app->user->identity->employee->ref_satker_id;
		$searchModel = new ActivityRoomSearch();
        $queryParams = Yii::$app->request->getQueryParams();
		$params = [];
		if($status=='all') $params = ['tb_room_id' => $tb_room_id,'ref_satker_id' => $ref_satker_id,];
		else $params = ['tb_room_id' => $tb_room_id,'ref_satker_id' => $ref_satker_id,'status' => $status,];
		$queryParams['ActivityRoomSearch']=$params;
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['startTime'=>SORT_DESC,'finishTime'=>SORT_DESC];*/
		
		$room = Room::findOne($id);
        return $this->render('calendar', [
			'room' => $room,
			'status' => $status,
        ]);
    }
	
	public function actionEventActivityRoom($id,$status='all')
	{       
		$start = Yii::$app->request->get('start');
		$end = Yii::$app->request->get('end');
		$items = array();
		if($status=='all'){
			$model= ActivityRoom::find()
					 ->where(' (`start` >= :start or `end` <= :end) and room_id=:room_id',
						[':start' => $start,':end' => $end,':room_id' => $id])
					 ->all();  
		}
		else{
			$model= \backend\models\ActivityRoom::find()
					 ->where(' (`start` >= :start or `end`<= :end) and room_id=:room_id and status=:status',
						[':start' => $start,':end' => $end,':room_id' => $id,':status' => $status])
					 ->all();   
		}
		$title = 'Untitle';
		/* $start = date('Y-m-d');
		$end = date('Y-m-d'); */
		$color = '';
		$link = '';
		foreach ($model as $value) {
			$activity = Activity::find()
				 ->where('id >= :id ',[':id' => $value->activity_id])
				 ->one();			
			
			$title = $activity->name;
			if($activity->satker_id!=Yii::$app->user->identity->employee->satker_id){
				$title.=' ['.$activity->satker->value.'] ';
			}
			$description = $title.' | '.\hscstudio\heart\helpers\Heart::twodate($value->start,$value->end,1);
			$start=date('Y-m-d H:i',strtotime($value->start));
			$end=date('Y-m-d H:i', strtotime('+1 minute', strtotime($value->end)));
			if($value->status==0) $color='#f0ad4e';
			else if($value->status==1) $color='#5bc0de';
			else if($value->status==2) $color='#5cb85c';
			else if($value->status==3) $color='#d9534f';
			$link = \yii\helpers\Url::to(['update-activity-room','id'=>$value->room_id,'activity_id'=>$activity->id]);			
				/* element.attr("title", event.description);
				element.attr("class", element.attr("class")+" "+event.class);
				element.attr("modal-size", event.modalSize);
				element.attr("modal-title", event.modalTitle);
				element.attr("data-toggle", event.dataToggle); */
			$items[]=[
				'title'=> $title,
				'start'=> $start,
				'end'=> $end,
				'color'=> $color,
				//'allDay'=>true,
				'url'=>$link,
				'description'=>$description,
				'class'=>'modal-heart',
				'modalSize'=>'modal-lg',
				'modalTitle'=>'Update Activity Room',
				'dataToggle'=>'tooltip',
				
			];
		}
		echo \yii\helpers\Json::encode($items);
    }
	
}
