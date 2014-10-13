<?php

namespace backend\modules\pusdiklat\planning\controllers;

use Yii;
use backend\models\Activity;
use backend\models\Training;
use backend\models\TrainingStudentPlan;
use backend\models\Program;
use backend\models\ProgramHistory;
use backend\models\Reference;
use backend\models\ObjectPerson;
use backend\modules\pusdiklat\planning\models\ActivitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hscstudio\heart\helpers\Heart;
use yii\helpers\ArrayHelper;
/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class Activity2Controller extends Controller
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
    public function actionIndex($year='',$status='nocancel')
    {
		if(empty($year)) $year=date('Y');
		$searchModel = new ActivitySearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status=='nocancel'){
			if($year=='all'){
				$queryParams['ActivitySearch']=[
					'status'=> [0,1,2],
				];
			}
			else{
				$queryParams['ActivitySearch']=[
					'year' => $year,
					'status'=> [0,1,2],
				];
			}
		}
		else if($status=='all'){
			if($year=='all'){
				$queryParams['ActivitySearch']=[
				];
			}
			else{
				$queryParams['ActivitySearch']=[
					'year' => $year,
				];
			}
		}
		else{
			if($year=='all'){
				$queryParams['ActivitySearch']=[
					'status' => $status,
				];
			}
			else{
				$queryParams['ActivitySearch']=[
					'year' => $year,
					'status' => $status,
				];
			}
		}
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		// GET ALL TRAINING YEAR
		$year_training = yii\helpers\ArrayHelper::map(Activity::find()
			->select(['year'=>'YEAR(start)','start','end'])
			->orderBy(['year'=>'DESC'])
			->groupBy(['year'])
			->currentSatker()
			->asArray()
			->all(), 'year', 'year');
		$year_training['all']='All'	;
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'year' => $year,
			'status' => $status,
			'year_training' => $year_training,
        ]);
    }

    /**
     * Displays a single Activity model.
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
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$training = Training::findOne(['activity_id'=>$model->id]);
		$renders=[];
		$renders['model'] = $model;
		$renders['training'] = $training;
		
		$program_revisions = yii\helpers\ArrayHelper::map(ProgramHistory::find()
			->select(['revision'])
			->where([
				'id' => $training->program_id
			])
			->orderBy(['revision'=>'DESC'])
			->groupBy(['revision'])
			->asArray()
			->all(), 'revision', 'revision');
		$renders['program_revisions'] = $program_revisions;
		
		if (Yii::$app->request->post()){ 
			$connection=Yii::$app->getDb();
			$transaction = $connection->beginTransaction();	
			try{
				if($model->load(Yii::$app->request->post())){
					$model->satker = 'current';								
					if($model->save()) {
						Yii::$app->getSession()->setFlash('success', 'Activity data have saved.');
						if($training->load(Yii::$app->request->post())){							
							$training->activity_id= $model->id;
							/* $training->program_revision = (int)\backend\models\ProgramHistory::getRevision($training->program_id); */
							
							if($training->save()){								 
								Yii::$app->getSession()->setFlash('success', 'Training & activity data have saved.');
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
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndexStudentPlan($year='',$status='nocancel')
    {
        if(empty($year)) $year=date('Y');
		$searchModel = new ActivitySearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status=='nocancel'){
			if($year=='all'){
				$queryParams['ActivitySearch']=[
					'status'=> [0,1,2],
				];
			}
			else{
				$queryParams['ActivitySearch']=[
					'year' => $year,
					'status'=> [0,1,2],
				];
			}
		}
		else if($status=='all'){
			if($year=='all'){
				$queryParams['ActivitySearch']=[
				];
			}
			else{
				$queryParams['ActivitySearch']=[
					'year' => $year,
				];
			}
		}
		else{
			if($year=='all'){
				$queryParams['ActivitySearch']=[
					'status' => $status,
				];
			}
			else{
				$queryParams['ActivitySearch']=[
					'year' => $year,
					'status' => $status,
				];
			}
		}
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		// GET ALL TRAINING YEAR
		$year_training = yii\helpers\ArrayHelper::map(Activity::find()
			->select(['year'=>'YEAR(start)','start','end'])
			->orderBy(['year'=>'DESC'])
			->groupBy(['year'])
			->currentSatker()
			->active()
			->asArray()
			->all(), 'year', 'year');
		$year_training['all']='All'	;

        return $this->render('indexStudentPlan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'year' => $year,
			'status' => $status,
			'year_training' => $year_training,
        ]);
    }
	
	/**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewStudentPlan($id)
    {
		$model = $this->findModel($id);
		$training = Training::findOne(['activity_id'=>$model->id]);
		$trainingStudentPlan = TrainingStudentPlan::findOne(['training_id'=>$model->id]);
		if(null==$trainingStudentPlan){
			$trainingStudentPlan = new TrainingStudentPlan(
				['training_id'=>$model->id]
			);
		}
		$renders=[];
		$renders['model'] = $model;
		$renders['training'] = $training;
		$renders['trainingStudentPlan'] = $trainingStudentPlan;
        if (Yii::$app->request->isAjax)
			return $this->renderAjax('viewStudentPlan', $renders);
		else
			return $this->render('viewStudentPlan', $renders);
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
			//1213020200 CEK KD_UNIT_ORG 1213020200 IN TABLE ORGANISATION IS SUBBIDANG KURIKULUM
			'organisation_1213020200'=>'PIC TRAINING ACTIVITY [BIDANG KURIKULUM]'
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
}
