<?php

namespace backend\modules\admin\controllers;

use Yii;
use backend\models\Employee;
use backend\models\EmployeeSearch;
use backend\models\ObjectReference;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hscstudio\heart\helpers\Heart;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
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
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
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
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($person_id='')
    {
        $model = new Employee();
		$renders = [];
		$renders['model'] = $model;
		$renders['person_id'] = $person_id;
		
		$object_references_array = [
			'unit'=>'Unit','finance_position'=>'Finance Position',
			'fungsional'=>'Fungsional','widyaiswara'=>'Widyaiswara','pranata_komputer'=>'Pranata Komputer',
			'pranata_komputer_terampil'=>'Pranata Komputer Terampil','pranata_komputer_ahli'=>'Pranata Komputer Ahli'];
		$renders['object_references_array'] = $object_references_array;
		foreach($object_references_array as $object_reference=>$label){
			$object_references[$object_reference]= new ObjectReference();
			$renders[$object_reference] = $object_references[$object_reference];
		}		
		
        if ($model->load(Yii::$app->request->post())){ 
			if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
				foreach($object_references_array as $object_reference=>$label){
					$reference_id = Yii::$app->request->post('ObjectReference')[$object_reference]['reference_id'];
					Heart::objectReference($object_references[$object_reference],$reference_id,'employee',$model->person_id,$object_reference);
				}
				return $this->redirect(['view', 'id' => $model->person_id]);
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
				return $this->render('create', $renders);
			}            
        } else {
            return $this->render('create', $renders);
        }
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		$renders = [];
		$renders['model'] = $model;
		
		$object_references_array = [
			'unit'=>'Unit','finance_position'=>'Finance Position',
			'fungsional'=>'Fungsional','widyaiswara'=>'Widyaiswara','pranata_komputer'=>'Pranata Komputer',
			'pranata_komputer_terampil'=>'Pranata Komputer Terampil','pranata_komputer_ahli'=>'Pranata Komputer Ahli'];		
		$renders['object_references_array'] = $object_references_array;
		foreach($object_references_array as $object_reference=>$label){
			$object_references[$object_reference] = ObjectReference::find()
				->where([
					'object'=>'employee',
					'object_id' => $id,
					'type' => $object_reference, 
				])
				->one();
			if($object_references[$object_reference]==null){
				$object_references[$object_reference]= new ObjectReference();
			}
			$renders[$object_reference] = $object_references[$object_reference];
		}	
		
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'Data have updated.');
				foreach($object_references_array as $object_reference=>$label){
					$reference_id = Yii::$app->request->post('ObjectReference')[$object_reference]['reference_id'];
					Heart::objectReference($object_references[$object_reference],$reference_id,'employee',$model->person_id,$object_reference);
				}
				return $this->redirect(['view', 'id' => $model->person_id]);
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
				return $this->render('update', $renders);
			}			
        } else {
            return $this->render('update', $renders);
        }
    }

    /**
     * Deletes an existing Employee model.
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
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
