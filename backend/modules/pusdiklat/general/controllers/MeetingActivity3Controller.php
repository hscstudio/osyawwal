<?php

namespace backend\modules\pusdiklat\general\controllers;

use Yii;
use backend\models\Activity;
use backend\models\ActivityHistory;
use backend\models\ActivityRoom;
use backend\models\Room;
use backend\models\Meeting;
use backend\models\Reference;
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
class MeetingActivity3Controller extends Controller
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
    public function actionIndex($year='',$status='all')
    {
		if(empty($year)) $year=date('Y');
		$searchModel = new ActivitySearch();
		$queryParams = Yii::$app->request->getQueryParams();
		$organisation_id = 391;
		if($status=='all'){
			if($year=='all'){
				$queryParams['ActivitySearch']=[
					'organisation_id' => $organisation_id
				];
			}
			else{
				$queryParams['ActivitySearch']=[
					'year' => $year,
					'organisation_id' => $organisation_id
				];
			}
		}
		else{
			if($year=='all'){
				$queryParams['ActivitySearch']=[
					'status' => $status,
					'organisation_id' => $organisation_id
				];
			}
			else{
				$queryParams['ActivitySearch']=[
					'year' => $year,
					'status' => $status,
					'organisation_id' => $organisation_id
				];
			}
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
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'year' => $year,
			'status' => $status,
			'year_meeting' => $year_meeting,
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
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Activity();		
		$meeting = new Meeting([
			'organisation_id'=>391
		]);
		$renders=[];
		$renders['model'] = $model;
		$renders['meeting'] = $meeting;
        if (Yii::$app->request->post()){ 
			$connection=Yii::$app->getDb();
			$transaction = $connection->beginTransaction();	
			try{
				if($model->load(Yii::$app->request->post())){					
					$model->satker = 'current';
					$model->location = implode('|',$model->location);
					$model->status =0;									
					if($model->save()) {
						Yii::$app->getSession()->setFlash('success', 'Activity data have saved.');
						if($meeting->load(Yii::$app->request->post())){							
							$meeting->activity_id= $model->id;	
							$meeting->organisation_id = 391;							
							if($meeting->save()){								 
								Yii::$app->getSession()->setFlash('success', 'Meeting & activity data have saved.');
								$transaction->commit();
								return $this->redirect(['view', 'id' => $model->id]);
							}
						}						
					}
					else{
						Yii::$app->getSession()->setFlash('error', 'Data is NOT saved.');
					}				
				}
			}
			catch (Exception $e) {
				Yii::$app->getSession()->setFlash('error', 'Roolback transaction. Data is not saved');
			}
        } 
		
		return $this->render('create', $renders);
    }

    /**
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->start = date('Y-m-d',strtotime($model->start));
		$model->end = date('Y-m-d',strtotime($model->end));
		$meeting = Meeting::findOne([
			'activity_id'=>$model->id,
			'organisation_id'=>391
		]);
		$renders=[];
		$renders['model'] = $model;
		$renders['meeting'] = $meeting;
		
		if (Yii::$app->request->post()){ 
			$connection=Yii::$app->getDb();
			$transaction = $connection->beginTransaction();	
			try{
				if($model->load(Yii::$app->request->post())){
					if (isset(Yii::$app->request->post()['create_revision'])){
						$model->create_revision = true;
					}
					$model->satker = 'current';
					$model->location = implode('|',$model->location);									
					if($model->save()) {
						Yii::$app->getSession()->setFlash('success', 'Activity data have saved.');
						if($meeting->load(Yii::$app->request->post())){							
							$meeting->activity_id= $model->id;
							if (isset(Yii::$app->request->post()['create_revision'])){
								$meeting->create_revision = true;
								
							}
							/* $meeting->program_revision = (int)\backend\models\ProgramHistory::getRevision($meeting->program_id); */
							// GENERATE TRAINING NUMBER
							if(Yii::$app->request->post('generate_number')){
								$year = date('Y',strtotime($model->start));
								$program = Program::find()->where(['id'=>$meeting->program_id])->currentSatker()->active()->one();
								$program_owner = sprintf("%02s", $program->satker->sort);
								$activity_owner = sprintf("%02s", $model->satker->sort);							
								if($program_owner==$activity_owner) $activity_owner='00';
								$program_number = $program->number;
								$meeting_of_program_this_year = Activity::find()
									->where('start<=:start and YEAR(start)=:this_year',[':start'=>$model->start,':this_year'=>$year])			
									->currentSatker()
									->active()
									->count()+1;
								$meeting->number = $year.'-'.$program_owner.'-'.$activity_owner.'-'.$program_number.'.'.$meeting_of_program_this_year;
							}
							
							if($meeting->save()){								 
								Yii::$app->getSession()->setFlash('success', 'Meeting & activity data have saved.');
								$transaction->commit();
								return $this->redirect(['view', 'id' => $model->id]);
							}
						}						
					}
					else{
						Yii::$app->getSession()->setFlash('error', 'Data is NOT saved.');
					}				
				}
			}
			catch (Exception $e) {
				Yii::$app->getSession()->setFlash('error', 'Roolback transaction. Data is not saved');
			}
        } 
		
		return $this->render('update', $renders);
    }

    /**
     * Deletes an existing Activity model.
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
		$satkers['all']='- All -';
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
	
}
