<?php

namespace backend\modules\pusdiklat\planning\controllers;

use Yii;
use backend\models\Program;
use backend\modules\pusdiklat\planning\models\ProgramSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\ObjectReference;
use backend\models\ObjectFile;
use backend\models\File;
use backend\models\ObjectPerson;
use backend\models\ProgramSubject;
use hscstudio\heart\helpers\Heart;
use yii\data\ActiveDataProvider;

/**
 * ProgramController implements the CRUD actions for Program model.
 */
class Program3Controller extends Controller
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
     * Lists all Program models.
     * @return mixed
     */
    public function actionIndex($status=1)
    {
        $searchModel = new ProgramSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status!='all'){
			$queryParams['ProgramSearch']=[
				'status'=>$status,
			];
		}
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'status' => $status,
        ]);
    }

    /**
     * Displays a single Program model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax)
			return $this->renderAjax('view', [
				'model' => $this->findModel($id),
			]);		
		else
			return $this->render('view', [
				'model' => $this->findModel($id),
			]);
    }

    

    /**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		
        if ($model->load(Yii::$app->request->post())) {
			$model->satker = 'current';
			/* $model->stage = implode(',',$model->stage); */
            if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'Data have updated.');
				
				return $this->redirect(['view', 'id' => $model->id]);
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
				return $this->render('update', $renders);
			}
			
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('update', $renders);
			else
				return $this->render('update', $renders);
        }
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
			//1213020300 CEK KD_UNIT_ORG 1213020300 IN TABLE ORGANISATION IS SUBBIDANG TENAGA PENGAJAR
			'organisation_1213020300'=>'PIC PROGRAM [BIDANG TENAGA PENGAJAR]'
		];
		$renders['object_people_array'] = $object_people_array;
		foreach($object_people_array as $object_person=>$label){
			$object_people[$object_person] = ObjectPerson::find()
				->where([
					'object'=>'program',
					'object_id' => $id,
					'type' => $object_person, 
				])
				->one();
			if($object_people[$object_person]==null){
				$object_people[$object_person]= new ObjectPerson([
					'object'=>'program',
					'object_id' => $id,
					'type' => $object_person, 
				]);
			}
			$renders[$object_person] = $object_people[$object_person];
		}	
		
        if (Yii::$app->request->post()) {
			foreach($object_people_array as $object_person=>$label){
				$person_id = (int)Yii::$app->request->post('ObjectPerson')[$object_person]['person_id'];
				Heart::objectPerson($object_people[$object_person],$person_id,'program',$id,$object_person);
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
     * Finds the Program model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Program the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		if (($model = Program::find()
				->where([
					'id'=>$id,
				])
				->currentSatker()
				->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	
	/**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubject($id,$status='all',$action='',$subject_id=0)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$renders['status'] = $status;
		
		if($status=='all'){
			$query = ProgramSubject::find()
				->where([
					'program_id' => $model->id,
				])
				->orderBy(['status'=>SORT_DESC,'sort'=>SORT_ASC,]);			
        }
		else if(in_array($status,[0,1])){
			$query = ProgramSubject::find()
				->where([
					'program_id' => $model->id,
					'status'=>$status,
				])
				->orderBy(['status'=>SORT_DESC,'sort'=>SORT_ASC,]);
		}
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);	
		//$dataProvider->getSort()->defaultOrder = ['type'=>SORT_ASC];		
		$renders['dataProvider'] = $dataProvider;
		
		if($action=='update'){
			$program_subject = ProgramSubject::findOne($subject_id);
		}
		
		if(!isset($program_subject) or null==$program_subject){
			$program_subject = new ProgramSubject([
				'program_id' => $model->id,
			]);			
		}
		$renders['program_subject'] = $program_subject;
		
        if (Yii::$app->request->post()) {
			$program_subject->load(Yii::$app->request->post());	
			$program_subject->stage = implode('|',$program_subject->stage);
			if($program_subject->save()){
				Yii::$app->getSession()->setFlash('success', 'Subject have saved.');					
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Subject is not saved.');
			}				
        }
		
		if (Yii::$app->request->isAjax)
			return $this->renderAjax('subject', $renders);
		else
			return $this->render('subject', $renders);
    } 
	
	public function actionSubjectDelete($id,$subject_id)
    {
		$model = $this->findModel($id);
		$program_subject = ProgramSubject::find()
			->where([
				'id'=>$subject_id,
				'program_id'=>$id,
			])
			->one();
		
		
		if(null!=$program_subject){
			if ($program_subject->delete()) Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
			else Yii::$app->getSession()->setFlash('error', 'Data is not deleted.');
		}
		else Yii::$app->getSession()->setFlash('error', 'Data is not deleted.');
        return $this->redirect(['subject','id'=>$model->id]);
    }
	
	public function actionSubjectStatus($id,$subject_id,$status)
    {
		$model = $this->findModel($id);
		$program_subject = ProgramSubject::find()
			->where([
				'id'=>$subject_id,
				'program_id'=>$id,
			])
			->one();
		
		
		if(null!=$program_subject){
			$status = ($status==1)?0:1;
			$program_subject->status = $status;
			if ($program_subject->save()) Yii::$app->getSession()->setFlash('success', 'Status updated.');
			else Yii::$app->getSession()->setFlash('error', 'Status is not updated.');
		}
		else Yii::$app->getSession()->setFlash('error', 'Status is not updates.');
        return $this->redirect(['subject','id'=>$model->id]);
    }
}
