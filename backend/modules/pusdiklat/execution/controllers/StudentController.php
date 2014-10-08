<?php

namespace backend\modules\pusdiklat\execution\controllers;

use Yii;
use backend\models\Student;
use backend\modules\pusdiklat\execution\models\StudentSearch;
use backend\models\Person;
use backend\modules\pusdiklat\execution\models\PersonSearch;
use backend\models\ObjectReference;
use backend\models\ObjectFile;
use backend\models\File;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hscstudio\heart\helpers\Heart;

/**
 * StudentController implements the CRUD actions for Student model.
 */
class StudentController extends Controller
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
     * Lists all Student models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Student model.
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
     * Creates a new Trainer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($person_id)
    {
		$person = Person::findOne($person_id);
		if (null==$person){  
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		
        $model = new Student([
			'person_id'=>$person_id,
		]);

        if ($model->load(Yii::$app->request->post())){ 
			if(strlen($person->nip)>3){
				$model->username = $person->nip;
				$model->password = $person->nip;				
				if($model->save()) {
					Yii::$app->getSession()->setFlash('success', 'New data have saved.');
				}
				else{
					Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
				}
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'NIP tidak boleh kosong.');
			}
            return $this->redirect(['person']);
        } else {
			if (Yii::$app->request->isAjax){
				return $this->renderAjax('create', [
					'model' => $model,
				]);
			}
			else{
				return $this->render('create', [
					'model' => $model,
				]);
			}
        }
    }

    /**
     * Updates an existing Student model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$person = Person::findOne($model->person_id);
		if (null==$person){  
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		$renders = [];
		$renders['model'] = $model;
		$renders['person'] = $person;
		$object_references_array = [
			'unit'=>'Unit','religion'=>'Religion','rank_class'=>'Rank Class','graduate'=>'Graduate'];
		$renders['object_references_array'] = $object_references_array;
		foreach($object_references_array as $object_reference=>$label){
			$object_references[$object_reference] = ObjectReference::find()
				->where([
					'object'=>'person',
					'object_id' => $person->id,
					'type' => $object_reference, 
				])
				->one();
			if($object_references[$object_reference]==null){
				$object_references[$object_reference]= new ObjectReference();
			}
			$renders[$object_reference] = $object_references[$object_reference];
		}	
		
		$currentFiles=[];
		$object_file_array = [
			'photo'=>'Photo 4x6','sk_cpns'=>'SK CPNS','sk_pangkat'=>'SK Pangkat'];
		$renders['object_file_array'] = $object_file_array;
		foreach($object_file_array as $object_file=>$label){
			$currentFiles[$object_file] = '';
			$object_files[$object_file] = ObjectFile::find()
				->where([
					'object'=>($object_file=='photo')?'person':'person',
					'object_id' => $person->id,
					'type' => $object_file, 
				])
				->one();
			
			if($object_files[$object_file]!=null){
				$files[$object_file] = File::find()
					->where([
						'id'=>$object_files[$object_file]->file_id, 
					])
					->one();
				$currentFiles[$object_file]=$files[$object_file]->file_name;
			}
			else{
				$object_files[$object_file]= new ObjectFile();
				$files[$object_file] = new File();
			}
			$renders[$object_file] = $object_files[$object_file];
			$renders[$object_file.'_file'] = $files[$object_file];
		}
		
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'Data student have updated.');
				$person->load(Yii::$app->request->post());
				
				//$person->scenario = 'profile';
				if($person->save()) {
					Yii::$app->getSession()->setFlash('success', 'Data student & person have updated.');
					foreach($object_references_array as $object_reference=>$label){
						$reference_id = Yii::$app->request->post('ObjectReference')[$object_reference]['reference_id'];
						Heart::objectReference($object_references[$object_reference],$reference_id,'person',$person->id,$object_reference);
					}		
					
					$uploaded_files = [];					
					foreach($object_file_array as $object_file=>$label){
						$uploaded_files[$object_file] = \yii\web\UploadedFile::getInstance($files[$object_file], 'file_name['.$object_file.']');						
						if(null!=$uploaded_files[$object_file]){
							//upload(
								//$instance_file, $object='person', $object_id, $file, $object_file, 
								//$type='photo', $resize=false,$current_file='',$thumb = false){
							Heart::upload(
								$uploaded_files[$object_file], 
								'person', 
								$person->id, 
								$files[$object_file],
								$object_files[$object_file], 
								$object_file, 
								($object_file=='photo')?true:false,
								$currentFiles[$object_file],
								($object_file=='photo')?true:false
							);					
						}
					}					
				}
				return $this->redirect(['index']);
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
			}
			return $this->redirect(['view', 'id' => $model->person_id]);
        } else {
            return $this->render('update', $renders);
        }
    }

    /**
     * Deletes an existing Student model.
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
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Student::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
     * Lists all Person models.
     * @return mixed
     */
    public function actionPerson()
    {		
        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('person', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
