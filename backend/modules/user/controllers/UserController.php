<?php

namespace backend\modules\user\controllers;

use Yii;
use backend\models\User;
use backend\models\Employee;
use backend\models\Person;
use backend\models\UserSearch;
use backend\models\ObjectReference;
use backend\models\ObjectFile;
use backend\models\File;
use hscstudio\heart\helpers\Heart;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentController implements the CRUD actions for Student model.
 */
class UserController extends Controller
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
    public function actionProfile()
    {
        $id = Yii::$app->user->id;
		$model = $this->findModel($id);
		//$model->scenario = 'profile';
		$employee = Employee::find()
					->where([
						'person_id' => $id
					])
					->one();
		$person = Person::find()
			->where([
				'id'=>$employee->person_id
			])
			->one();			
		if (null==$employee or null==$person){  
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		
		$renders = [];
		$renders['model'] = $model;
		$renders['employee'] = $employee;
		$renders['person'] = $person;
		$object_references_array = [
			'religion'=>'Religion','rank_class'=>'Rank Class','graduate'=>'Graduate'];
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
		
        if (Yii::$app->request->post()) {
            Yii::$app->getSession()->setFlash('success', 'Data student have updated.');
			$person->load(Yii::$app->request->post());
			//$person->scenario = 'profile';
			if($person->save()) {
				Yii::$app->getSession()->setFlash('success', 'Data user & person have updated.');
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
				return $this->redirect(['profile']);
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
				return $this->render('profile', $renders);
			}			
        } else {
            return $this->render('profile', $renders);
        }
    }

	/**
     * Lists all Student models.
     * @return mixed
     */
    public function actionPassword()
    {
        $id = Yii::$app->user->id;
		$model = $this->findModel($id);
		$model->scenario = 'password';
        if ($model->load(Yii::$app->request->post())) {	
			$model->old_password = Yii::$app->security->generatePasswordHash($model->old_password);
			if(!Yii::$app->security->validatePassword($model->old_password, Yii::$app->user->identity->password_hash)){
				Yii::$app->getSession()->setFlash('error', 'Old password not match with current password');
			}
			else{
				$model->password = $model->new_password;
				if($model->save()) {
					Yii::$app->getSession()->setFlash('success', 'Password have changed.');				
				}
				else{
					Yii::$app->getSession()->setFlash('error', 'Password is not change.');
				}
			}
			return $this->redirect(['password']);
        } else {
            return $this->render('password', [
                'model' => $model,
            ]);
        }
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
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
