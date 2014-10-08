<?php

namespace backend\modules\pusdiklat\planning\controllers;

use Yii;
use backend\models\TrainingSubjectTrainerRecommendation;
use backend\modules\pusdiklat\planning\models\TrainingSubjectTrainerRecommendationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrainingSubjectTrainerRecommendationController implements the CRUD actions for TrainingSubjectTrainerRecommendation model.
 */
class TrainingSubjectTrainerRecommendationController extends Controller
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
     * Lists all TrainingSubjectTrainerRecommendation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrainingSubjectTrainerRecommendationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrainingSubjectTrainerRecommendation model.
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
     * Creates a new TrainingSubjectTrainerRecommendation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TrainingSubjectTrainerRecommendation();

        if ($model->load(Yii::$app->request->post())){ 
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
     * Updates an existing TrainingSubjectTrainerRecommendation model.
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
     * Deletes an existing TrainingSubjectTrainerRecommendation model.
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
     * Finds the TrainingSubjectTrainerRecommendation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainingSubjectTrainerRecommendation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrainingSubjectTrainerRecommendation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
