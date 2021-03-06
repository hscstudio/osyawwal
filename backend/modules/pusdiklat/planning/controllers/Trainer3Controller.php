<?php

namespace backend\modules\pusdiklat\planning\controllers;

use Yii;
use backend\models\Trainer;
use backend\modules\pusdiklat\planning\models\TrainerSearch;
use backend\models\Person;
use backend\modules\pusdiklat\planning\models\PersonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\ObjectReference;
use backend\models\ObjectFile;
use backend\models\File;
use hscstudio\heart\helpers\Heart;

/**
 * Trainer3Controller implements the CRUD actions for Trainer model.
 */
class Trainer3Controller extends Controller
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
     * Lists all Trainer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrainerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Trainer model.
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
        $model = new Trainer([
			'person_id'=>$person_id,
		]);

        if ($model->load(Yii::$app->request->post())){ 
			if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
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
     * Updates an existing Trainer model.
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
			return $this->redirect(['view', 'id' => $model->person_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Trainer model.
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
     * Finds the Trainer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Trainer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Trainer::findOne($id)) !== null) {
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
	
	/**
     * Displays a single Person model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewPerson($id)
    {
        return $this->render('view_person', [
            'model' => $this->findModelPerson($id),
        ]);
    }

    /**
     * Creates a new Person model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatePerson()
    {
        $model = new Person();
		$trainer = new Trainer();
		$renders = [];
		$renders['model'] = $model;
		$renders['trainer'] = $trainer;
		
		$object_references_array = [
			'religion'=>'Religion','rank_class'=>'Rank Class','graduate'=>'Graduate'];
		$renders['object_references_array'] = $object_references_array;
		foreach($object_references_array as $object_reference=>$label){
			$object_references[$object_reference]= new ObjectReference();
			$renders[$object_reference] = $object_references[$object_reference];
		}	
		
		$currentFiles=[];
		$object_file_array = [
			'photo'=>'Photo 4x6','sk_cpns'=>'SK CPNS','sk_pangkat'=>'SK Pangkat'];
		$renders['object_file_array'] = $object_file_array;
		foreach($object_file_array as $object_file=>$label){
			$currentFiles[$object_file] = '';
			$object_files[$object_file]= new ObjectFile();
			$files[$object_file] = new File();
			$renders[$object_file] = $object_files[$object_file];
			$renders[$object_file.'_file'] = $files[$object_file];
		}
		
        if ($model->load(Yii::$app->request->post())){ 
			$model->status = 1;
			if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
				foreach($object_references_array as $object_reference=>$label){
					$reference_id = Yii::$app->request->post('ObjectReference')[$object_reference]['reference_id'];
					Heart::objectReference($object_references[$object_reference],$reference_id,'person',$model->id,$object_reference);
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
							$model->id, 
							$files[$object_file],
							$object_files[$object_file], 
							$object_file, 
							($object_file=='photo')?true:false,
							$currentFiles[$object_file],
							($object_file=='photo')?true:false
						);					
					}
				}
				
				return $this->redirect(['view-person', 'id' => $model->id]);
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
				return $this->render('create_person', $renders);
			}
            
        } else {
            return $this->render('create_person', $renders);
        }
    }

    /**
     * Updates an existing Person model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdatePerson($id)
    {
        $model = $this->findModelPerson($id);
		$trainer = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$renders['trainer'] = $trainer;
		$object_references_array = [
			'religion'=>'Religion','rank_class'=>'Rank Class','graduate'=>'Graduate'];
		$renders['object_references_array'] = $object_references_array;
		foreach($object_references_array as $object_reference=>$label){
			$object_references[$object_reference] = ObjectReference::find()
				->where([
					'object'=>'person',
					'object_id' => $id,
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
					'object_id' => $model->id,
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
				Yii::$app->getSession()->setFlash('success', 'Data Person telah diupdate.');
				if ($trainer->load(Yii::$app->request->post())) {
					if($trainer->save()){
						Yii::$app->getSession()->setFlash('success', 'Data Pengajar telah diupdate.');
					}
				}
				 
				foreach($object_references_array as $object_reference=>$label){
					$reference_id = Yii::$app->request->post('ObjectReference')[$object_reference]['reference_id'];
					Heart::objectReference($object_references[$object_reference],$reference_id,'person',$model->id,$object_reference);
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
							$model->id, 
							$files[$object_file],
							$object_files[$object_file], 
							$object_file, 
							($object_file=='photo')?true:false,
							$currentFiles[$object_file],
							($object_file=='photo')?true:false
						);					
					}
				}
				return $this->redirect(['view-person', 'id' => $model->id]);
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
				return $this->render('update_person', $renders);
			}
			
        } else {
            return $this->render('update_person', $renders);
        }
    }

    /**
     * Deletes an existing Person model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletePerson($id)
    {
		$model = $this->findModelPerson($id);
		// delete object_reference
		ObjectReference::deleteAll(
			'object = :object AND object_id = :object_id', 
			[':object' => 'person', ':object_id' => $id]
		);
		// find all object_file
		$object_files = ObjectFile::find()
			->where([
				'object' => 'person',
				'object_id' => $id,
			])
			->all();
		foreach($object_files as $object_file){	
			$files = File::find()
			->where([
				'id' => $object_file->file_id,
			])
			->one();
			if($files!=null){
				$path = '';
				if(isset(Yii::$app->params['uploadPath'])){
					$path = Yii::$app->params['uploadPath'].'/'.$object_file->object.'/'.$object_file->object_id.'/';
				}
				else{
					$path = Yii::getAlias('@file').'/'.$object_file->object.'/'.$object_file->object_id.'/';
				}
				$filename = $files->file_name;
				@mkdir($path, 0755, true);
				@chmod($path, 0755);
				@unlink($path . $filename);
				@unlink($path . 'thumb_'. $filename);
				File::find(
					'id = :id',
					[':id' => $files->id]
				)
				->one()
				->delete();
			}
		}
		ObjectFile::deleteAll(
			'object = :object AND object_id = :object_id', 
			[':object' => 'person', ':object_id' => $id]
		);
		
		if($model->delete()) {			
			Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
		}
		else{
			Yii::$app->getSession()->setFlash('error', 'Data is not deleted.');
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the Person model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelPerson($id)
    {
        if (($model = Person::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
