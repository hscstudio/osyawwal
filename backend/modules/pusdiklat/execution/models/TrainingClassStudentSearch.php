<?php

namespace backend\modules\pusdiklat\execution\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TrainingClassStudent;

/**
 * TrainingClassStudentSearch represents the model behind the search form about `backend\models\TrainingClassStudent`.
 */
class TrainingClassStudentSearch extends TrainingClassStudent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'training_id', 'training_class_id', 'training_student_id', 'head_class', 'status', 'created_by', 'modified_by'], 'integer'],
            [['number', 'created', 'modified'], 'safe'],
            [['activity', 'presence', 'pre_test', 'post_test', 'test'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TrainingClassStudent::find()
			->joinWith('trainingStudent')
			->joinWith('trainingStudent.student')
			->joinWith('trainingStudent.student.person');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['defaultOrder'=>'person.name asc']
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'training_id' => $this->training_id,
            'training_class_id' => $this->training_class_id,
            'training_student_id' => $this->training_student_id,
            'head_class' => $this->head_class,
            'activity' => $this->activity,
            'presence' => $this->presence,
            'pre_test' => $this->pre_test,
            'post_test' => $this->post_test,
            'test' => $this->test,
            'training_class_student.status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number]);

        return $dataProvider;
    }
}
