<?php
namespace backend\modules\pusdiklat\execution\controllers;

use Yii;
use backend\models\Activity;
use backend\modules\pusdiklat\execution\models\ActivitySearch;
use yii\helpers\Html;
use backend\models\Person;
use backend\models\ObjectPerson;
use backend\models\ObjectFile;
use backend\models\Program;
use backend\models\ProgramSubject;
use backend\models\Reference;
use backend\models\ObjectReference;
use backend\models\Training;
use backend\models\TrainingStudentPlan;

use backend\models\TrainingClass;
use backend\modules\pusdiklat\execution\models\TrainingClassSearch;

use backend\models\TrainingClassSubject;
use backend\modules\pusdiklat\execution\models\TrainingClassSubjectSearch;

use backend\models\TrainingClassStudent;
use backend\modules\pusdiklat\execution\models\TrainingClassStudentSearch;

use backend\models\TrainingStudent;
use backend\modules\pusdiklat\execution\models\TrainingStudentSearch;

use backend\models\TrainingSchedule;
use backend\modules\pusdiklat\execution\models\TrainingScheduleSearch;
use backend\modules\pusdiklat\execution\models\TrainingScheduleExtSearch;

use backend\models\Student;
use backend\modules\pusdiklat\execution\models\StudentSearch;

use backend\models\Room;
use backend\models\ActivityRoom;
use backend\modules\pusdiklat\execution\models\ActivityRoomSearch;
use backend\modules\pusdiklat\execution\models\ActivityRoomExtensionSearch;
use backend\modules\pusdiklat\execution\models\RoomSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

use hscstudio\heart\helpers\Heart;
use yii\data\ArrayDataProvider;
/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class ActivityController extends Controller
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
		$model->start = date('Y-m-d',strtotime($model->start));
		$model->end = date('Y-m-d',strtotime($model->end));
		$training = Training::findOne(['activity_id'=>$model->id]);
		$renders=[];
		$renders['model'] = $model;
		$renders['training'] = $training;
		
		if (Yii::$app->request->post()){ 
			$connection=Yii::$app->getDb();
			$transaction = $connection->beginTransaction();	
			try{
				if($model->load(Yii::$app->request->post())){
					$model->satker = 'current';
					$model->location = implode('|',$model->location);							
					if($model->save()) {
						Yii::$app->getSession()->setFlash('success', 'Activity data have saved.');
						if($training->load(Yii::$app->request->post())){							
							$training->activity_id= $model->id;
							$training->program_revision = (int)\backend\models\ProgramHistory::getRevision($training->program_id);
							
							if($training->save()){								 
								Yii::$app->getSession()->setFlash('success', 'Training & activity data have saved.');
								$transaction->commit();
								return $this->redirect(['index']);
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
			//1213030100 CEK KD_UNIT_ORG 1213030100 IN TABLE ORGANISATION IS SUBBIDANG PENYEL I
			'organisation_1213030100'=>'PIC TRAINING ACTIVITY [BIDANG PENYELENGGARAAN I]'
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
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionDashboard($id)
    {
		$model = $this->findModel($id);
		
        return $this->render('dashboard', [
            'model' => $model,
        ]);
    }
	
	/**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionProperty($id)
    {
        $model = $this->findModel($id);
		$program = Program::findOne($model->training->program_id);
		$subject = new ActiveDataProvider([
            'query' => ProgramSubject::find()
						->where([
							'program_id' => $model->training->program_id,
							'status'=>1,
						])
						->orderBy(['sort'=>SORT_ASC,]),
        ]);
		$document = new ActiveDataProvider([
            'query' => ObjectFile::find()
						->joinWith('file')
						->where([
							'object'=>'program',
							'object_id' => $model->training->program_id,
							'file.status'=>1,
							'type' => ['kap','gbpp','module'],
						])
						->orderBy(['type'=>SORT_ASC,]),
        ]);
		return $this->render('property', [
            'model' =>$model,
			'program' =>$program,
			'subject' =>$subject,
			'document' =>$document,
        ]);
    }
	
	 /**
     * Lists all TrainingClass models.
     * @return mixed
     */
    public function actionClass($id)
    {
        $model = $this->findModel($id);
		$searchModel = new TrainingClassSearch([
			'training_id' => $id,
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$subquery = TrainingClassStudent::find()
			->select('training_student_id')
			->where(['training_id' => $id]);
		 
		// fetch orders that are placed by customers who are older than 30  
		$trainingStudentCount = TrainingStudent::find()
			->where(['status'=>'1'])
			->andWhere([
				'not in', 'id', $subquery
			])
			->count();
		
		if (Yii::$app->request->post()){ 
			$student = Yii::$app->request->post()['student'];
			$baseon = 0;
			if(isset(Yii::$app->request->post()['baseon'])) $baseon = Yii::$app->request->post()['baseon'];
			$objectTrainingClass = TrainingClass::find()
					->where([
						'training_id' => $id,
					]);
			$trainingClassCount = (int) $objectTrainingClass->count();
			
			if($student>$trainingStudentCount){
				Yii::$app->session->setFlash('error', 'Your request more than stock!');
			}
			else if($trainingClassCount==0){
				Yii::$app->session->setFlash('error', 'There is no class!');
			}
			else if($baseon==0 or count($baseon)==0){
				Yii::$app->session->setFlash('error', 'Select base on random!');
			}
			else{				
				$baseon = implode(',',$baseon);
				// select name, gender from person group by name, gender order by rand();
				$part = (int) ($student / $trainingClassCount);
				$residu = $student % $trainingClassCount;
				$trainingClasses = $objectTrainingClass->all();
				//die($part.' => '.$residu);
				$idx = 1;
				foreach($trainingClasses as $trainingClass){
					$limit = $part;
					if($idx == $trainingClassCount){
						$limit += $residu; 
					}
					$trainingStudents = TrainingStudent::find()
						->joinWith('student')
						->joinWith('student.person')
						->joinWith('student.person.unit')
						->where(['training_student.status'=>'1'])
						->andWhere([
							'not in', 'training_student.id', $subquery
						])
						->groupBy($baseon)
						->orderBy('rand()')
						->limit($limit)
						->asArray()
						->all();
					foreach ($trainingStudents as $trainingStudent){
						$trainingClassStudent = new TrainingClassStudent([
							'training_id'=>$id,
							'training_class_id'=>$trainingClass->id,
							'training_student_id'=>$trainingStudent['id'],
							'status'=>1
						]);
						$trainingClassStudent->save();
					}
					$idx++;
				}
				
				Yii::$app->session->setFlash('success', $student.' student added!');
				
				$subquery = TrainingClassStudent::find()
					->select('training_student_id')
					->where(['training_id' => $id]);
				 
				// fetch orders that are placed by customers who are older than 30  
				$trainingStudentCount = TrainingStudent::find()
					->where(['status'=>'1'])
					->andWhere([
						'not in', 'id', $subquery
					])
					->count();
			}
		}
		
        return $this->render('class', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
			'trainingStudentCount' => $trainingStudentCount
        ]);
    }
	
	/**
     * Creates a new TrainingClass model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateClass($id)
    {		
		$model = $this->findModel($id);
		$classCount1=$model->training->class_count_plan;
		$classCount2=TrainingClass::find()->where(['training_id' =>$model->id])->count();
		$createClass = $classCount1 - $classCount2;
		// x = 1 - 0 = 1
		// start = 0
		// finish = 0+x-1
		if($createClass>0){
			$start = $classCount2;
			$finish = $classCount2+$createClass-1;
			$classes = \hscstudio\heart\helpers\Heart::abjad($start,$finish);
			$created=0;
			$failed=0;
			foreach($classes as $class){
				echo "<br>".$class;
				$model = new TrainingClass();
				$model->training_id = $id;
				$model->class = $class;
				$model->status = 1;
				if($model->save()){
					$created++;
				}
				else{
					$failed++;
				}				
			}
			
			if($failed>0){
				Yii::$app->session->setFlash('warning', $created.' class created but '.$failed.' class failed');
			}
			else{
				Yii::$app->session->setFlash('success', $created.' class created');
			}
		}
		else{
			Yii::$app->session->setFlash('warning', 'No class created');
		}
		
		return $this->redirect(['class', 'id' => $id]);
    }
	
	/**
     * Deletes an existing TrainingClass model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteClass($id, $class_id)
    {
        $model = $this->findModelClass($class_id);
		$model->delete();
		Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
        return $this->redirect(['class', 'id' => $id]);
    }
	
    /**
     * Finds the TrainingClass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainingClass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelClass($id)
    {
        if (($model = TrainingClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
     * Lists all TrainingClassSubject models.
     * @return mixed
     */
    public function actionClassSubject($id, $class_id)
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class		
			
		$searchModel = new TrainingClassSubjectSearch();
		$queryParams['TrainingClassSubjectSearch']=[				
			'training_class_id' =>$class_id,
			'status'=>1,
		];
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams); 
		
        return $this->render('classSubject', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'activity' => $activity, 
			'class' => $class, 
        ]);
    }
	
	 /**
     * Creates a new TrainingClassSubject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateClassSubject($id,$class_id)
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class
		
		$programSubjects= ProgramSubject::find()
			->where([
				'program_id' => $activity->training->program_id,
				'status'=>1
			])
			->all();
		$created=0;
		$failed=0;
		foreach($programSubjects as $programSubject){
			$classSubjectCount = TrainingClassSubject::find()
					->where([
						'training_class_id' => $class_id,
						'program_subject_id' => $programSubject->id,
					])
					->count();
			if ($classSubjectCount==0){
				$classSubject = new TrainingClassSubject([
						'training_class_id' => $class_id,
						'program_subject_id' => $programSubject->id,
						'status'=>1,
					]);
				if($classSubject->save()){
					$created++;
				}
				else{
					$failed++;
				}
			}
		}
		
		if($failed>0){
			Yii::$app->session->setFlash('warning', $created.' class subject created but '.$failed.' class failed');
		}
		else if($created==0){
			Yii::$app->session->setFlash('warning', 'No class subject created');
		}
		else{
			Yii::$app->session->setFlash('success', $created.' class subject created, awesome :)');
		}
		
		return $this->redirect(['class', 'id' => $id]);	
		
    }
	
	/**
     * Lists all TrainingClass models.
     * @return mixed
     */
    public function actionStudent($id)
    {
        $model = $this->findModel($id);
		$searchModel = new TrainingStudentSearch([
			'training_id' => $id,
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('student', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
        ]);
    }
	
	/**
     * Lists all TrainingClass models.
     * @return mixed
     */
    public function actionChooseStudent($id)
    {
        $model = $this->findModel($id);
		$searchModel = new StudentSearch([
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('chooseStudent', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
        ]);
    }
	
	/**
     * Updates an existing TrainingStudent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateStudent($id,$student_id)
    {
        $model = $this->findModel($id);
		$training_student = TrainingStudent::find()
			->where([
				'training_id'=>$model->id,
				'student_id'=>$student_id,
			])
			->one();
        if ($training_student->load(Yii::$app->request->post())) {
            if($training_student->save()) {
				Yii::$app->getSession()->setFlash('success', 'Data have updated.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
			}
			return $this->redirect(['student', 'id' => $model->id]);
        } 
		
		if (Yii::$app->request->isAjax)
			return $this->renderAjax('updateStudent', [
				'model' => $model,
				'training_student' => $training_student,
			]);
		else
			return $this->render('updateStudent', [
				'model' => $model,
				'training_student' => $training_student,
			]);
    }
	
	 /**
     * Finds the TrainingClass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainingClass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelStudent($id)
    {
        if (($model = TrainingStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	 /**
     * Creates a new TrainingStudent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSetStudent($id,$student_id)
    {
		$model = $this->findModel($id);
		$student = Student::findOne($student_id);
		$training_student = new TrainingStudent([
				'training_id'=>$model->id,
				'student_id'=>$student_id,
			]);

        if (Yii::$app->request->post()){ 
			if($training_student->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
			}
            return $this->redirect(['choose-student', 'id' => $model->id]);
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('setStudent', [
					'model' => $model,
					'student' => $student,
					'training_student' => $training_student,
				]);
			else
				return $this->render('setStudent', [
					'model' => $model,
					'student' => $student,
					'training_student' => $training_student,
				]);
        }
    }

	/**
     * Deletes an existing TrainingClass model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteStudent($id, $student_id, $training_student_id)
    {
        $model = $this->findModelStudent($training_student_id);
		$model->delete();
		Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
        return $this->redirect(['student', 'id' => $id]);
    }
	
	public function actionImportStudent($id){	
		$model = $this->findModel($id);
		$session = Yii::$app->session;
		$session->open();
		
		$start = time();
		if (!empty($_FILES)) {
			$importFile = \yii\web\UploadedFile::getInstanceByName('importFile');			
			if(!empty($importFile)){
				$fileTypes = ['xls','xlsx']; 
				$ext=$importFile->extension;
				if(in_array($ext,$fileTypes)){
					$inputFileType = \PHPExcel_IOFactory::identify($importFile->tempName );
					$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($importFile->tempName );
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$baseRow = 4;
					$err=[];
					$data = [];	
					$password = Yii::$app->security->generatePasswordHash('aku cinta bppk');
					$row=0;
					while(!empty($sheetData[$baseRow]['B'])){
						// GET DATA FROM EXCEL
						$nip = str_replace(' ','',trim($sheetData[$baseRow]['B']));
						$name = trim($sheetData[$baseRow]['C']);						
						$unit = trim($sheetData[$baseRow]['D']);
						$trainingStudent = TrainingStudent::find()
							->where([ 
								'training_id' => $model->id ,
								'student_id' => Person::find()
									->select('id')
									->where(['nip' => $nip])
									->orWhere(['nid' => $nip])
									->column(),
							])
							->exists();
						if(!$trainingStudent){						
							$row++;
							$person_id = 0;
							$person = Person::find()
										->where(['nip' => $nip])
										->orWhere(['nid' => $nip])
										->one();
							if(null!=$person){
								$person_id = $person->id;
							}
							
							$student_id = 0;
							$student = Student::find()
								->where([ 
									'person_id' => Person::find()
										->select('id')
										->where(['nip' => $nip])
										->orWhere(['nid' => $nip])
										->column(),
								])
								->one();					
							if(null!=$student){
								$student_id = $student->person_id;
							}
							
							$reference_id = 0;
							$reference = Reference::find()
								->where([
									'type'=>'unit',
									'value'=>$unit
								])
								->one();
							if(null!=$reference){
								$reference_id = $reference->id;
							}
							
							$object_reference_id = 0;
							$object_reference = ObjectReference::find()
								->where([
									'object' => 'person',
									'object_id' => $person->id,
									'type' => 'unit',
								])
								->one();
							if(null!=$object_reference){
								$object_reference_id = 1;
							}
						
							
							$data[$row]=[
								'row'	=>	$row,
								'nip'	=>	$nip,
								'name'	=>	$name,
								'unit'	=>	$unit,
								'unit_id' => $reference_id,
								'student_id'=>	$student_id,
								'person_id'=>	$person_id,
								'password' => $password,
								'object_reference_id' => $object_reference_id,
							];							
						}
						$baseRow++;
					}	
					
					// SESSION BUG MAYBE :)
					$data[$row+1]=[];
					$session['data'] = $data;						
					Yii::$app->session->setFlash('success', ($row).' row affected');
					
					
					/* if(!empty($err)){
						Yii::$app->session->setFlash('warning', 'There are error: <br>'.implode('<br>',$err));
					} */	
				}
				else{
					Yii::$app->session->setFlash('error', 'Filetype allowed only xls and xlsx');
					return $this->redirect([
						'student', 'id' => $id
					]);
				}				
			}
			else{
				Yii::$app->session->setFlash('error', 'File import empty!');
				return $this->redirect([
					'student', 'id' => $id
				]);
			}
		}
		
		if (Yii::$app->request->post() and isset(Yii::$app->request->post()['selection'])){ 
			$selections = Yii::$app->request->post()['selection'];
			$names = Yii::$app->request->post()['name'];
			$nips = Yii::$app->request->post()['nip'];
			$unit_ids = Yii::$app->request->post()['unit_id'];
			$student_ids = Yii::$app->request->post()['student_id'];
			$person_ids = Yii::$app->request->post()['person_id'];
			$passwords = Yii::$app->request->post()['password'];
			$object_reference_ids =  Yii::$app->request->post()['object_reference_id'];
			
			/* $person_values = [];
			$student_values = [];
			$training_student_values = []; */
			foreach($selections as $selection){	
				/* if($persons[$selection]==0)
					$person_values[] = [$names[$selection],$nips[$selection],$nips[$selection],1];
				if($students[$selection]==0)
					$student_values[] = [$nips[$selection]]; */	
				$person_id = $person_ids[$selection];
				$name = $names[$selection];
				$nip = $nips[$selection];
				$unit_id = $unit_ids[$selection];
				$student_id = $student_ids[$selection];
				$object_reference_id = $object_reference_ids[$selection];
				// PROVIDE DATA FROM NIP
				$birthday = '';
				$gender = '';
				$status = 1;
				
				if(strlen($nip)==18){
					$birthday = substr($nip,0,4) .'-'. substr($nip,4,2) .'-'. substr($nip,6,2);
					$gender = substr($nip,14,1);
				}
				$password = $passwords[$selection];
				if($person_id==0){
					$person = new Person([
						'name' => $name,
						'nid' => $nip,
						'nip' => $nip,
						'birthday' => $birthday,
						'gender' => $gender,
						'status' => 1,
					]);
					if($person->save()){
						$person_id = $person->id;
					}
				}
				
				if($person_id>0){
					if($student_id==0){
						$student = new Student([
							'person_id' => $person_id,
							'username'=>$nip,
							'password_hash'=>$password,
							'status' => 1,
						]);
						if($student->save()){
							$student_id = $student->person_id;
						}
					}
					
					if($student_id>0){
						$training_student = new TrainingStudent([
							'student_id' => $student_id,
							'training_id' => $id,
							'status' => 1,
						]);
						$training_student->save();
					}
					
					if($unit_id>0){
						/* if($object_reference_id==1){
							$object_reference = ObjectReference::find()
								->where([
									'object' => 'person',
									'object_id' => $person_id,
									'type' => 'unit',
									'reference_id' => $unit_id,
								]);
						}
						else{
							$object_reference = new ObjectReference([
								'object' => 'person',
								'object_id' => $person_id,
								'type' => 'unit',
								'reference_id' => $unit_id,
							]);
						}
						$object_reference->save(); */
					}
				} 
			}
			
			unset($session['data']);
			
			/*
			if(count($person_values)>0)
				Yii::$app->getDb()->createCommand()
					->batchInsert('person', ['name', 'nip', 'nid','status'], $person_values)
					->execute();
			
			if(count($student_values)>0)
				Yii::$app->getDb()->createCommand()
					->batchInsert('student', ['person_id','username','password_hash','status'], $student_values)
					->execute();
			 Yii::$app->getDb()->createCommand()
				->batchInsert('training_student', ['nip'], $person_values);
				->execute(); */	
			/* Yii::$app->getDb()->createCommand()
				->batchInsert('tbl_user', ['name', 'status'],
					[],
					[],
					
				])
				->execute(); */

		}
		/* 
		Please read guide of upload https://github.com/yiisoft/yii2/blob/master/docs/guide/input-file-upload.md
		maybe I do mistake :)
		*/	
		

		
		if ($session->has('data')) {
			$data = $session['data'];
			$dataProvider = new ArrayDataProvider([
				'allModels' => $data,
				/* 'sort' => [
					'attributes' => ['row', 'nip', 'name', 'unit', 'exists'],
				], */
				'pagination' => [
					'pageSize' => 'all',
				],
			]);
			
			return $this->render('importStudent', [
				'model' => $model,
				'dataProvider' => $dataProvider,
			]);
		}
		else{
			return $this->redirect([
				'student', 'id' => $id
			]);
		}
	}
	
	/**
     * Lists all TrainingClassSubject models.
     * @return mixed
     */
    public function actionClassStudent($id, $class_id)
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class		
			
		$searchModel = new TrainingClassStudentSearch();
		$queryParams['TrainingClassStudentSearch']=[				
			'training_class_id' =>$class_id,
			'training_class_student.status'=>1,
		];
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams); 
		/* $dataProvider->getSort()->defaultOrder = ['name'=>SORT_ASC]; */
		
		$subquery = TrainingClassStudent::find()
			->select('training_student_id')
			->where(['training_id' => $id]);
		 
		// fetch orders that are placed by customers who are older than 30  
		$trainingStudentCount = TrainingStudent::find()
			->where(['status'=>1])
			->andWhere([
				'not in', 'id', $subquery
			])
			->count();
		
		if (Yii::$app->request->post()){ 
			$student = Yii::$app->request->post()['student'];
			$baseon = 0;
			if(isset(Yii::$app->request->post()['baseon'])) $baseon = Yii::$app->request->post()['baseon'];
			if($student>$trainingStudentCount){
				Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Your request more than stock!');
			}
			else if($baseon==0 or count($baseon)==0){
				Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Select base on random!');
			}
			else{				
				$baseon = implode(',',$baseon);
				// select name, gender from person group by name, gender order by rand();
				$trainingStudents = TrainingStudent::find()
					->joinWith('student')
					->joinWith('student.person')
					->joinWith('student.person.unit')
					->where(['training_student.status'=>'1'])
					->andWhere([
						'not in', 'training_student.id', $subquery
					])
					->groupBy($baseon)
					->orderBy('rand()')
					->limit($student)
					->asArray()
					->all();
				foreach ($trainingStudents as $trainingStudent){
					$trainingClassStudent = new TrainingClassStudent([
						'training_id'=>$id,
						'training_class_id'=>$class_id,
						'training_student_id'=>$trainingStudent['id'],
						'status'=>1
					]);
					$trainingClassStudent->save();
				}
				Yii::$app->session->setFlash('success', $student.' student added!');
				
				$subquery = TrainingClassStudent::find()
					->select('training_student_id')
					->where(['training_id' => $id]);
				 
				// fetch orders that are placed by customers who are older than 30  
				$trainingStudentCount = TrainingStudent::find()
					->where(['status'=>'1'])
					->andWhere([
						'not in', 'id', $subquery
					])
					->count();
			}
		}
		
        return $this->render('classStudent', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'activity' => $activity, 
			'class' => $class, 
			'trainingStudentCount' => $trainingStudentCount
        ]);
    }
	
	/**
     * Deletes an existing TrainingClass model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteClassStudent($id, $class_id, $training_class_student_id)
    {
        $model = $this->findModelClassStudent($training_class_student_id);
		$model->delete();
		
		Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
        return $this->redirect(['class-student', 'id' => $id, 'class_id' => $class_id]);
    }
	
	protected function findModelClassStudent($id)
    {
        if (($model = TrainingClassStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionChangeClassStudent($id, $class_id, $training_class_student_id)
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		$model = $this->findModelClassStudent($training_class_student_id);
		$renders = [];
		$renders['activity'] = $activity;
		$renders['class'] = $class;
		$renders['model'] = $model;
		$trainingClass = TrainingClass::find()
				->all();
		$renders['trainingClass'] = $trainingClass;	
		if (Yii::$app->request->post()) {			
			$model->load(Yii::$app->request->post());
			if($model->save()){
				Yii::$app->getSession()->setFlash('success', 'Student have moved.');
				if (!Yii::$app->request->isAjax){
					return $this->redirect(['class-student', 'id' => $id, 'class_id'=>$class_id]);	
				}
				else{
					echo 'Student have moved.';
				}
			}
			else{
				Yii::$app->getSession()->setFlash('failed', 'Student have not moved.');
				if (!Yii::$app->request->isAjax) {
					return $this->redirect(['class-student', 'id' => $id, 'class_id'=>$class_id]);	
				}
				else{
					echo 'Student have not moved.';
				}
			}
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('changeClassStudent', $renders);
            else
				return $this->render('changeClassStudent', $renders);
        }
    }
	
	/**
     * Lists all Room models.
     * @return mixed
     */
    public function actionRoom($id)
    {
		$model = $this->findModel($id);
		
		$searchModel = new ActivityRoomSearch([
			'activity_id' => $id,
		]);
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$location = explode('|',$model->location);
		$location = (int)@$location[0];		
		
		$searchActivityRoomModel = new ActivityRoomExtensionSearch([
			'startDateX'=>date('Y-m-d',strtotime($model->start)),
			'endDateX'=>date('Y-m-d',strtotime($model->end)),
			'computer'=>0,
			'hostel'=>0,
			'capacity'=>20,
			'location'=>$location,
		]);
		
		// SEARCH ROOM
		/* $computer = 0;
		$hostel = 0;
		$capacity = 20;
		$satker_id = $location;
		
		if (Yii::$app->request->post()) {	
			$post = Yii::$app->request->post();
			$computer = $post['computer'];
			$hostel = $post['hostel'];
			$capacity = $post['capacity'];
			$satker_id = $post['location'];
		}
		
		$searchModel2 = new RoomSearch([
			'computer'=>$computer,
			'hostel'=>$computer,
			'capacity'=>$computer,
			'satker_id'=>$satker_id,
			'status'=>1
		]);
			
        $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams); */
		
		$satkers['all']='--- All ---';
		$satkers = ArrayHelper::map(Reference::find()
			->where([
				'type'=>'satker',
			])
			->asArray()
			->all(), 'id', 'name');
			
        return $this->render('room', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			/* 'searchModel2' => $searchModel2,
            'dataProvider2' => $dataProvider2, */
			'model' => $model,
			'satkers'=>$satkers,
			'searchActivityRoomModel' => $searchActivityRoomModel,
        ]);
    }
	
	/**
     * Lists all Room models.
     * @return mixed
     */
    public function actionAvailableRoom($id)
    {
		$model = $this->findModel($id);
		
		if (Yii::$app->request->post()) {
			$post = Yii::$app->request->post('ActivityRoomExtensionSearch');
			$wheres = [];
			if($post['computer']==1) $wheres[] = 'computer=1';
			if($post['hostel']==1) $wheres[] = 'hostel=1';
			if($post['capacity']>0) $wheres[] = 'capacity>='.$post['capacity'];
			if($post['location']!='all') $wheres[] = 'satker_id='.$post['location'];
			$wheres[] = 'status = 1';
			$where = implode(' AND ',$wheres);
			$room = Room::find()->where(
				$where
			)->all();
			
			$start = date('Y-m-d H:i',strtotime($post['startDateX'].' '.$post['startTimeX'])); 
			$end = date('Y-m-d H:i',strtotime($post['endDateX'].' '.$post['endTimeX'])); 
			echo "<label><strong>List of Available Room</strong></label>";
			echo '<div class="table-responsive">
			<table class="table table-hover table-bordered table-striped table-condensed">
			<thead>
			<tr>
				<th class="kv-sticky-column kv-align-center kv-align-middle" style="width:60px;">No</th>
				<th class="kv-sticky-column kv-align-center kv-align-middle">Room</th>
				<th class="kv-sticky-column kv-align-center kv-align-middle" style="width:60px;">Capa</th>
				<th class="kv-sticky-column kv-align-center kv-align-middle" style="width:60px;">Comp</th>
				<th class="kv-sticky-column kv-align-center kv-align-middle" style="width:60px;">Host</th>
				<th class="kv-sticky-column kv-align-center kv-align-middle" style="width:60px;">Action</th>
			</th>
			</thead>
			<tbody>';
			$idx=0;
			$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
			foreach($room as $data){				
				// ONLY CHECK AVAILABILITY
				$activityRoom = ActivityRoom::find()
						->where('
							((start between :start AND :end)
								OR (end between :start AND :end))
							AND 
							room_id = :room_id
							AND
							status = :status
						',
						[
							':start' => $start,
							':end' => $end,
							':room_id' => $data->id,
							':status' => 2,
						]);
						
				// IS AVAILABLE			
				if($activityRoom->count()==0){ 
					$activityRoom2 = ActivityRoom::find()
							->where('
								room_id = :room_id 
								AND
								activity_id = :activity_id
								AND
								status!=3
							',
							[
								':room_id' => $data->id,
								':activity_id' => $model->id,
							]);
					if($activityRoom2->count()==0){ 
						$idx++;
						echo '<tr>';
						echo '<td>'.$idx.'</td>';
						echo '<td>';
						echo $data->name;
						if($data->satker_id!=$satker_id){
							echo '<br><span class="badge">'.$data->satker->name.'</span>';
						}
						echo '</td>';
						echo '<td class="kv-sticky-column kv-align-center kv-align-middle">'.$data->capacity.'</td>';
						echo '<td class="kv-sticky-column kv-align-center kv-align-middle">'.$data->computer.'</td>';
						echo '<td class="kv-sticky-column kv-align-center kv-align-middle">'.$data->hostel.'</td>';
						echo '<td class="kv-sticky-column kv-align-center kv-align-middle">';
						echo Html::a('<span class="fa fa-square-o"></span>', 
							[
							'set-room',
							'id'=>$model->id,
							'room_id'=>$data->id
							], 
							[
							'class' => 'label label-info link-post','data-pjax'=>0,
							'title'=>'click to set it!',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
							]);
						echo '</td>';
						echo '</tr>';
					}
					else{
						//echo '<tr>';
						//echo '<td colspan="6">unavailable.. coming soon :)</td>';
						//echo '</tr>';
					}
				}
				// IS NOT AVAILABLE	
				else{
					echo '<tr>';
					echo '<td colspan="6">unavailable.. coming soon :)</td>';
					echo '</tr>';
				}
			}
			echo '
			</tbody>
			</table>
			</div>
			<hr>';
			echo '<script>			
					$( "a.link-post" ).click(function() {
						if(!confirm("Are you sure set it??")) return false;	
						$.ajax({
							url: $(this).attr("href"),
							type: "post",
							data: $("#form-available-room").serialize(),
							success: function(data) {
								$("#form-available-room").submit();
								$.pjax.reload({
									url: "'.\yii\helpers\Url::to(['room','id'=>$model->id]).'",
									container: "#pjax-gridview-room", 
									timeout: 3000,
								});							
							},
							error:  function( jqXHR, textStatus, errorThrown ) {
								$("#available-room").html(jqXHR.responseText);
							}
						});	
						return false;
					});
				 </script>';
		}
	}
	/**
     * Creates a new Meeting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSetRoom($id,$room_id)
    {
		$model=$this->findModel($id);	
		$post = Yii::$app->request->post('ActivityRoomExtensionSearch'); 		
		$room = Room::findOne($room_id);
		$satker_id = (int)Yii::$app->user->identity->employee->satker_id;		
		$status = ($room->satker_id==$satker_id)?1:0;
		$start = date('Y-m-d H:i',strtotime($post['startDateX'].' '.$post['startTimeX'])); 
		$end = date('Y-m-d H:i',strtotime($post['endDateX'].' '.$post['endTimeX']));
		
        $activityRoom = new ActivityRoom();
		$activityRoom->activity_id = (int)$id;
		$activityRoom->room_id = (int)$room_id;
		$activityRoom->start = $start;
		$activityRoom->end = $end;
		$activityRoom->status = $status;
		
        if($activityRoom->save()) {
			Yii::$app->session->setFlash('success', 'Room have setted');
		}
		else{
			 Yii::$app->session->setFlash('error', 'Unable set, there are some error');
		}
		
		if (Yii::$app->request->isAjax){	
			return ('Room have setted');
		}
		else{
			return $this->redirect(['room', 'id' => $id]);
		} 
    }
	
	public function actionUnsetRoom($id,$room_id)
    {
		$model=$this->findModel($id);	
		$post = Yii::$app->request->post(); 		
		$room = Room::findOne($room_id);
		$satker_id = (int)Yii::$app->user->identity->employee->satker_id;		
		$activityRoom = ActivityRoom::find()->where(
			'activity_id=:activity_id AND room_id=:room_id',
			[
				':activity_id'=>$id,':room_id'=>$room_id
			])
			->one();
		$msg="-";
		
		if($room->satker_id==$satker_id and $activityRoom->status!=1){
			if (Yii::$app->request->isAjax){	
				$msg = ('You have not privileges to unset this data.');
			}
			else{
				Yii::$app->session->setFlash('error', 'You have not privileges to unset this data.');
			}
		}
		else if($room->satker_id!=$satker_id and $activityRoom->status!=0){
			if (Yii::$app->request->isAjax){	
				$msg = ('You have not privileges to unset this data..');
			}
			else{
				Yii::$app->session->setFlash('error', 'You have not privileges to unset this data..');
			}
		}
		else
		{
			if($activityRoom->delete()) {
				Yii::$app->session->setFlash('success', 'Room have unset');
			}
			else{
				 Yii::$app->session->setFlash('error', 'Unable unset there are some error');
			}
		}

		if (Yii::$app->request->isAjax){	
			echo $msg;
		}
		else{
			return $this->redirect(['room', 'id' => $id]);
		}		
		
    } 

	/**
     * Lists all Room models.
     * @return mixed
     */
    public function actionClassSchedule($id, $class_id,$start="",$end="")
    {
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	

		if(empty($start)){
			$start = $class->training->activity->start;
		}
		
		if(empty($end) or $end<$start){
			$end = $start;
		}
		$searchModel = new TrainingScheduleSearch();
		$queryParams['TrainingScheduleSearch']=[				
			'training_class_id'=>$class_id,
			'startDate' => date('Y-m-d',strtotime($start)),
			'endDate'=>date('Y-m-d',strtotime($start)),
		];		
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];

		$trainingScheduleExtSearch = new TrainingScheduleExtSearch([
			'startDate' => date('Y-m-d',strtotime($start)),
			'scheduleDate' => date('Y-m-d',strtotime($start)),
		]);
		
		// GET ALL TRAINING YEAR
		if (Yii::$app->request->isAjax){
			return $this->renderAjax('classSchedule', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'activity'=>$activity,
				'class'=>$class,
				'start'=>$start,
				'end'=>$end,
				'trainingScheduleExtSearch' => $trainingScheduleExtSearch
			]);
		}
		else{
			return $this->render('classSchedule', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'activity'=>$activity,
				'class'=>$class,
				'start'=>$start,
				'end'=>$end,
				'trainingScheduleExtSearch' => $trainingScheduleExtSearch
			]);
		}
    }
	
	public function actionClassScheduleMaxTime($id, $class_id,$start=""){
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	

		if(empty($start)){
			$start = $class->training->activity->start;
		}
		
		$start = date('Y-m-d',strtotime($start)); 
		$end =  date('Y-m-d',(strtotime($start)+ 60*60*24));
		$trainingSchedule = TrainingSchedule::find()
				->where('
					(
						start >= :start AND 
						end <= :end
					)
					AND 
						training_class_id = :class_id
					AND
					status = :status
				',
				[
					':start' => $start,
					':end' => $end,
					':class_id' => $class_id,
					':status' => 1,
				])
				->orderBy('end DESC')
				->one();
		if($trainingSchedule!=null)
			return date('H:i',strtotime($trainingSchedule->end));		
		else
			return '08:00';		
	}
	
	public function actionAddActivityClassSchedule($id, $class_id) 
    { 	
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		if (Yii::$app->request->isAjax){
			// PREPARING DATA
			$post = Yii::$app->request->post('TrainingScheduleExtSearch');
			$start = date('Y-m-d H:i',strtotime($post['startDate'].' '.$post['startTime'])); 
			$training_class_subject_id = $post['training_class_subject_id'];
			$activity_room_id =  $post['activity_room_id'];
			$activity_name = "";
			$pic = "";
			$hours = 0;
			$minutes = 0;
			
			//CHECKING SATKER
			$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
			if ($satker_id!=$activity->satker_id){
				die('|0|You have not privileges');
			}
			
			if(empty($training_class_subject_id) or $training_class_subject_id==0){
				die('|0|You must select activity!');
			}
			else if ($training_class_subject_id>0){
				$hours = $post['hours'];
				if($hours>0){
					$minutes = (int)($hours * 45);
				}
				else{
					die('|0|Hours have more than 0');
				}
			}
			else{
				$minutes = (int)$post['minutes'];
				if($minutes>0){
					$activity_name = $post['activity'];
					$pic = $post['pic'];
				}
				else{
					die('|0|Minutes have more than 0');
				}
			}
			$training_class_subject_id=(int)$training_class_subject_id;
			$end = date('Y-m-d H:i',strtotime($start)+($minutes*60));
			$activity_room_id=(int)$activity_room_id;
			// CHECKING CONSTRAIN TIME
			$startSearch = $start + 1; // [08:00 - 09:00, 09:00 - 10:00] not exact between :)
			$endSearch = $end - 1;
			$trainingSchedule = TrainingSchedule::find()
				->where('
					((start between :start AND :end)
						OR (end between :start AND :end))
					AND 
						training_class_id = :training_class_id
					AND
					status = :status
				',
				[
					':start' => $startSearch,
					':end' => $endSearch,
					':training_class_id' => $class_id,
					':status' => 1,
				]);
				
			// IS NOT CONSTRAIN			
			if($trainingSchedule->count()==0){ 
				// PREPARING SAVE
				$model = new TrainingSchedule(); 
				$model->training_class_id=$class_id;
				$model->training_class_subject_id = $training_class_subject_id;
				$model->activity_room_id = $activity_room_id;		
				$model->activity = $activity_name;
				$model->pic = $pic;
				$model->hours = $hours;
				$model->start = $start;
				$model->end = $end;
				$model->status = 1;
			
				if($model->save()) {
					Yii::$app->session->setFlash('success', 'Activity have Added');
					die('|1|Activity have Added|'.date('Y-m-d',strtotime($start)).'|'.date('H:i',strtotime($end)));
					
				}
				else{
					die('|0|There are some error');
				}
			}
			else{
				die('|0|Constrain time, please change time!');
			}
		}
		else{
			Yii::$app->session->setFlash('error', 'Only for ajax request');
			return $this->redirect(['class-schedule', 'id' => $id, 'class_id' => $class_id]);
		}
    } 
	
	public function actionDeleteActivityClassSchedule($id, $class_id, $schedule_id) 
    { 	
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		if (Yii::$app->request->isAjax){
			//CHECKING SATKER
			$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
			if ($satker_id!=$activity->satker_id){
				die('|0|You have not privileges');
			}
			
			$trainingSchedule = \backend\models\TrainingSchedule::find()->where([
				'id'=>$schedule_id,
				'training_class_id'=>$class_id,
			])->one();
			$start = $trainingSchedule->start;
			if($trainingSchedule->delete()) {
				Yii::$app->session->setFlash('success', 'Delete activity success');
				die('|1|Activity have deleted|'.date('Y-m-d',strtotime($start)).'|'.date('H:i',strtotime($start)));
			}
			else{
				die('|0|There are some error');
			}
		}
		else{
			Yii::$app->session->setFlash('error', 'Only for ajax request');
			return $this->redirect(['class-schedule', 'id' => $id, 'class_id' => $class_id]);
		} 
	}
	
	public function actionRoomClassSchedule($id, $class_id, $schedule_id) 
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		if (Yii::$app->request->isAjax){
			$model = TrainingSchedule::findOne($schedule_id);
			$dataRoom = [];
			$activityRoom = ActivityRoom::find()
				->where([
					'activity_id'=>$activity->id,
					'status'=>2 // Approved only	
				])
				->all();
			foreach ($activityRoom as $ar){
				$dataRoom[$ar->room_id] = $ar->room->name;
			}
			if (Yii::$app->request->post()) {	
				$model->activity_room_id = Yii::$app->request->post('TrainingSchedule')['activity_room_id'];
				if($model->save()) {
					Yii::$app->session->setFlash('success', 'Room have set');
					die('|1|Room have set|'.date('Y-m-d',strtotime($model->start)).'|'.date('H:i',strtotime($model->end)));
					
				}
				else{
					die('|0|There are some error');
				}
			}
			else{
				return $this->renderAjax('roomSchedule', [
					'model' => $model,
					'activity' => $activity,
					'class' => $class,
					'dataRoom' => $dataRoom
				]);
			}
		}
		else{
			Yii::$app->session->setFlash('error', 'Only for ajax request');
			return $this->redirect(['class-schedule', 'id' => $id, 'class_id' => $class_id]);
		} 
    }
	
	public function actionTrainerClassSchedule($id, $class_id, $schedule_id) 
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		if (Yii::$app->request->isAjax){
			$model = new \backend\models\TrainingScheduleTrainer();
			$trainingSchedule = TrainingSchedule::findOne($schedule_id);
			$trainingSubjectTrainerRecommendation =\backend\models\TrainingSubjectTrainerRecommendation::find()
			->where([
				'training_id'=>$activity->id,
				'program_subject_id'=>$trainingSchedule->trainingClassSubject->program_subject_id,
				'status'=>1,
			])
			->groupBy('type')
			->all();
			if ($model->load(Yii::$app->request->post())) {
				$trainer_id_array = Yii::$app->request->post('trainer_id_array');
				
				$insert=0;
				foreach($trainer_id_array as $trainer_id=>$on){
					$model2 = new \backend\models\TrainingScheduleTrainer();
					$model2->training_schedule_id = $schedule_id;
					$trainingSubjectTrainerRecommendation=\backend\models\TrainingSubjectTrainerRecommendation::find()
					->where([
						'training_id'=>$activity->id,
						'program_subject_id'=>$trainingSchedule->trainingClassSubject->program_subject_id,
						'trainer_id'=>$trainer_id,
						'status'=>1,
					])
					->one();
					$model2->type=$trainingSubjectTrainerRecommendation->type;
					$model2->trainer_id = $trainer_id;
					$model2->status = 1;
					if($model2->save()) {
						$insert++;
					}
					else{
						die('|0|There are some error'.print_r($model2->errors));
					}
				}
				
				if($insert>0) {
					Yii::$app->session->setFlash('success', 'Trainer have added');
					die('|1|Trainer have added|'.date('Y-m-d',strtotime($trainingSchedule->start)).'|'.date('H:i',strtotime($trainingSchedule->end)));
				}
				else{
					die('|0|No trainer added');
				} 
			}
			else{				
				return $this->renderAjax('trainerSchedule', [
					'model' => $model,
					'activity' => $activity,
					'class' => $class,
					'trainingSchedule' => $trainingSchedule,
					'trainingSubjectTrainerRecommendation' => $trainingSubjectTrainerRecommendation,
					
				]);
			}
		}
		else{
			Yii::$app->session->setFlash('error', 'Only for ajax request');
			return $this->redirect(['class-schedule', 'id' => $id, 'class_id' => $class_id]);
		} 
    }
	
	 /**
     * Lists all TrainingClass models.
     * @return mixed
     */
    public function actionHonorarium($id)
    {
        $model = $this->findModel($id);
		$searchModel = new TrainingClassSearch([
			'training_id' => $id,
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('honorarium', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
        ]);
    }
	
	public function actionPrepareHonorarium($id, $class_id)
    {				
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		
		/* $dataProvider = new \yii\data\ActiveDataProvider([
			'query' => \backend\models\TrainingScheduleTrainer::find()
				->joinWith(['trainingSchedule'])
				->where([
					'tb_training_schedule_id'=>\backend\models\TrainingSchedule::find()
						->select('id')
						->where([
							'tb_training_class_id'=>$tb_training_class_id,
							'status'=>1,					
						])
						->andWhere('tb_training_class_subject_id>0')
						->groupBy('tb_training_class_subject_id')
						->column(),
					TrainingScheduleTrainer::tableName().'.status'=>1,
					'ref_trainer_type_id'=>[0], //Only PENGAJAR not ASISTEN & PENCERAMAH
				])
				->groupBy('tb_training_class_subject_id,tb_trainer_id'),				
			'pagination' => [
				'pageSize' => 20,
			],
			'sort'=> ['defaultOrder' => ['tb_training_schedule_id'=>SORT_ASC]]
		]);
		$trainingClass=\backend\models\TrainingClass::findOne($tb_training_class_id);
		$sbu = \backend\models\Sbu::find()->where(['name'=>'honor_persiapan_mengajar','status'=>1])->one();
        return $this->render('prepare', [
			'dataProvider' => $dataProvider,
			'trainingClass' => $trainingClass, 
			'sbu' => $sbu,
        ]); */
    }
	
	/* public function actionTransport($tb_training_class_id)
    {			
		$ref_satker_id = Yii::$app->user->identity->employee->ref_satker_id;
		$dataProvider = new ActiveDataProvider([
			'query' => TrainingScheduleTrainer::find()
				->select(TrainingScheduleTrainer::tableName().'.*,'.Employee::tableName().'.ref_satker_id')
				->joinWith(['trainer', 'trainer.employee','trainingSchedule'])
				->where([
					'tb_training_schedule_id'=>TrainingSchedule::find()
						->select('id')
						->where([
							'tb_training_class_id'=>$tb_training_class_id,
							'status'=>1,					
						])
						->andWhere('tb_training_class_subject_id>0')
						//->groupBy('tb_training_class_subject_id')
						->column(),
					TrainingScheduleTrainer::tableName().'.status'=>1,
				])
				->andWhere(
					'('.Employee::tableName().'.ref_satker_id IS NOT NULL AND '.Employee::tableName().'.ref_satker_id!='.$ref_satker_id.')'.
					' OR '.
					Employee::tableName().'.ref_satker_id IS NULL'
				)
				->groupBy('tb_training_class_subject_id,tb_trainer_id')
				,	
			'pagination' => [
				'pageSize' => 20,
			],
			'sort'=> ['defaultOrder' => ['tb_training_schedule_id'=>SORT_ASC]]
		]);
		$trainingClass=TrainingClass::findOne($tb_training_class_id);
		$sbu = Sbu::find()->where(['name'=>'honor_transport_dalam_kota','status'=>1])->one();
        return $this->render('transport', [
			'dataProvider' => $dataProvider,
			'trainingClass' => $trainingClass, 
			'sbu' => $sbu,
        ]);
    }
	
	public function actionTraining($tb_training_class_id)
    {			
		$ref_satker_id = Yii::$app->user->identity->employee->ref_satker_id;
		$dataProvider = new ActiveDataProvider([
			'query' => TrainingScheduleTrainer::find()
				->joinWith(['trainer', 'trainer.employee','trainingSchedule'])
				->where([
					'tb_training_schedule_id'=>TrainingSchedule::find()
						->select('id')
						->where([
							'tb_training_class_id'=>$tb_training_class_id,
							'status'=>1,					
						])
						->andWhere('tb_training_class_subject_id>0')
						//->groupBy('tb_training_class_subject_id')
						->column(),
					TrainingScheduleTrainer::tableName().'.status'=>1,
				])
				->groupBy('tb_training_class_subject_id,tb_trainer_id')
				,		
			'pagination' => [
				'pageSize' => 20,
			],
			'sort'=> ['defaultOrder' => ['tb_training_schedule_id'=>SORT_ASC,'ref_trainer_type_id'=>SORT_ASC]]
		]);

		$trainingClass=TrainingClass::findOne($tb_training_class_id);
		$sbus = ArrayHelper::map(Sbu::find()->where(['status'=>1])->all(),'name','value');
        return $this->render('training', [
			'dataProvider' => $dataProvider,
			'trainingClass' => $trainingClass, 
			'sbus' => $sbus,
        ]);
    } */
}
