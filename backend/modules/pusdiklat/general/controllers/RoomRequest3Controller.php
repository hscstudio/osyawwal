<?php

namespace backend\modules\pusdiklat\general\controllers;

use Yii;
use backend\models\Activity;
use backend\models\ActivityHistory;
use backend\models\ActivityRoom;
use backend\models\Room;
use backend\models\Meeting;
use backend\models\Reference;
use backend\models\Organisation;
use backend\models\ObjectPerson;
use backend\modules\pusdiklat\general\models\MeetingActivitySearch as ActivitySearch;;
use backend\modules\pusdiklat\general\models\RoomSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hscstudio\heart\helpers\Heart;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class RoomRequest3Controller extends Controller
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
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex($year='',$status='all', $organisation_id='387')
    {
		if(empty($year)) $year=date('Y');
		$searchModel = new ActivitySearch();
		$queryParams = Yii::$app->request->getQueryParams();		
		if($status!='all'){
			$queryParams['ActivitySearch']['status'] = $status;
		}
		
		$org = Organisation::findOne($organisation_id);
		if($org->KD_ESELON==2){
		
		}
		else {
			$queryParams['ActivitySearch']['organisation_id'] = $organisation_id;
		}
		
		if($year!='all'){
			$queryParams['ActivitySearch']['year'] = $year;
		}
				
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		// GET ALL TRAINING YEAR
		$year_meeting = yii\helpers\ArrayHelper::map(Activity::find()
			->select(['year'=>'YEAR(start)','start','end'])
			->orderBy(['year'=>'DESC'])
			->groupBy(['year'])
			->currentSatker()
			->asArray()
			->all(), 'year', 'year');
		$year_meeting['all']='All'	;
		
		// GET ALL ORGANISATION
		$organisation['all']='- All -';
		$organisations = yii\helpers\ArrayHelper::map(Organisation::find()
			->where([
				'KD_UNIT_ES2'=>'13',
				'KD_ESELON'=>[2,4],
			])
			->asArray()
			->all(), 'ID', 'NM_UNIT_ORG');
			
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'year' => $year,
			'status' => $status,
			'year_meeting' => $year_meeting,
			'organisations' => $organisations,
			'organisation_id' => $organisation_id
        ]);
    }
	
    /**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		
        $query = ActivityHistory::find()
			->joinWith('meeting')
			->where([
				'satker_id' => $satker_id,
				'id' => $id,				
			]);
			
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		$dataProvider->getSort()->defaultOrder = ['revision'=>SORT_DESC];
		
		return $this->render('view', [
            'model' => $this->findModel($id),
			'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionProgramName($id){
		$model = Program::find()->where([
			'id'=>$id
		])
		->currentSatker()
		->active()
		->one();
		return $model->name;
	}
	
	
	
	/**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPic($id)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$object_people_array = [
			// CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
			'organisation_391'=>'PIC Meeting'
		];
		$renders['object_people_array'] = $object_people_array;
		foreach($object_people_array as $object_person=>$label){
			$object_people[$object_person] = ObjectPerson::find()
				->where([
					'object'=>'activity',
					'object_id' => $id,
					'type' => $object_person, 
				])
				->one();
			if($object_people[$object_person]==null){
				$object_people[$object_person]= new ObjectPerson(
					[
						'object'=>'activity',
						'object_id' => $id,
						'type' => $object_person, 
					]
				);
			}
			$renders[$object_person] = $object_people[$object_person];
		}	
		
        if (Yii::$app->request->post()) {
			foreach($object_people_array as $object_person=>$label){
				$person_id = (int)Yii::$app->request->post('ObjectPerson')[$object_person]['person_id'];
				Heart::objectPerson($object_people[$object_person],$person_id,'activity',$id,$object_person);
			}	
			Yii::$app->getSession()->setFlash('success', 'Pic have updated.');
			if (!Yii::$app->request->isAjax) {
				return $this->redirect(['view', 'id' => $model->id]);	
			}
			else{
				echo 'Pic have updated.';
			}
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('pic', $renders);
            else
				return $this->render('pic', $renders);
        }
    }
	
	 /**
     * Lists all Room models.
     * @return mixed
     */
    public function actionRoom($activity_id, $satker_id=0)
    {
		$activity=$this->findModel($activity_id);
		
        $searchModel = new RoomSearch();
		if($satker_id===0) $satker_id = (int)$activity->location;
		if($satker_id<0) $satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		if($satker_id=='all'){
			$queryParams['RoomSearch']=[
				'status'=>1,
			];
		}
		else{
			$queryParams['RoomSearch']=[
				'satker_id'=>$satker_id,
				'status'=>1,
			];
		}	
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);

		// GET ALL TRAINING YEAR
		$satkers['all']='All';
		$satkers = yii\helpers\ArrayHelper::map(\backend\models\Reference::find()
			->where(['type'=>'satker'])
			//->orderBy(['eselon'=>'ASC',])
			//->active()
			->asArray()
			->all(), 'id', 'name');
		
		if (Yii::$app->request->isAjax){
			return $this->renderAjax('room', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'activity_id'=>$activity_id,
				'activity'=>$activity,
				'satker_id'=>$satker_id,
				'satkers'=>$satkers,
			]);
		}
		else{
			return $this->render('room', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'activity_id'=>$activity_id,
				'activity'=>$activity,
				'satker_id'=>$satker_id,
				'satkers'=>$satkers,
			]);
		}
    }
	
	/**
     * Creates a new Meeting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSetRoom($activity_id,$room_id,$status)
    {
        $satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		$activity=$this->findModel($activity_id);		
		$meeting = Meeting::findOne($activity->id);
		$room = Room::findOne($room_id);
		/* $status = 0;
		if($room->satker_id == $satker_id) $status = 1; */
		$model = new ActivityRoom([
			'activity_id'=>$meeting->activity_id,
			'room_id'=>$room->id,
			'start'=>$activity->start,
			'end'=>$activity->end,
			'status'=>$status,
		]);
		
        if($model->save()) {
			Yii::$app->session->setFlash('success', 'Data saved');
		}
		else{
			 Yii::$app->session->setFlash('error', 'Unable create there are some error');
		}
		if (Yii::$app->request->isAjax){	
			return ('Room have set');
		}
		else{
			return $this->redirect(['room', 'activity_id' => $activity_id]);
		}
    }
	
	 public function actionUnsetRoom($activity_id,$room_id)
    {
        $model = ActivityRoom::find()->where(
			'activity_id=:activity_id AND room_id=:room_id',[':activity_id'=>$activity_id,':room_id'=>$room_id])->one();
		if($model->delete()) {
			Yii::$app->session->setFlash('success', 'Data saved');
		}
		else{
			 Yii::$app->session->setFlash('error', 'Unable create there are some error');
		}
		if (Yii::$app->request->isAjax){	
			return ('Room have unset');
		}
		else{
			return $this->redirect(['room', 'activity_id' => $activity_id]);
		}
    }
}
