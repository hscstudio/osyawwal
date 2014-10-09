<?php

namespace backend\modules\pusdiklat\execution\controllers;

use Yii;
use backend\models\ActivityRoom;
use backend\modules\pusdiklat\execution\models\ActivityRoomSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ActivityRoomController implements the CRUD actions for ActivityRoom model.
 */
class ActivityRoomController extends Controller
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
     * Lists all ActivityRoom models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ActivityRoomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ActivityRoom model.
     * @param integer $activity_id
     * @param integer $room_id
     * @return mixed
     */
    public function actionView($activity_id, $room_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($activity_id, $room_id),
        ]);
    }

    /**
     * Creates a new ActivityRoom model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ActivityRoom();

        if ($model->load(Yii::$app->request->post())){ 
			if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
			}
            return $this->redirect(['view', 'activity_id' => $model->activity_id, 'room_id' => $model->room_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ActivityRoom model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $activity_id
     * @param integer $room_id
     * @return mixed
     */
    public function actionUpdate($activity_id, $room_id)
    {
        $model = $this->findModel($activity_id, $room_id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'Data have updated.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
			}
			return $this->redirect(['view', 'activity_id' => $model->activity_id, 'room_id' => $model->room_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ActivityRoom model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $activity_id
     * @param integer $room_id
     * @return mixed
     */
    public function actionDelete($activity_id, $room_id)
    {
		if($this->findModel($activity_id, $room_id)->delete()) {
			Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
		}
		else{
			Yii::$app->getSession()->setFlash('error', 'Data is not deleted.');
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the ActivityRoom model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $activity_id
     * @param integer $room_id
     * @return ActivityRoom the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($activity_id, $room_id)
    {
        if (($model = ActivityRoom::findOne(['activity_id' => $activity_id, 'room_id' => $room_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
