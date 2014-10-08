<?php

namespace frontend\modules\student\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\TrainingStudent;

/**
 * TrainingStudentSearch represents the model behind the search form about `frontend\models\TrainingStudent`.
 */
class TrainingStudentSearch extends TrainingStudent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'training_id', 'student_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['note', 'created', 'modified'], 'safe'],
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
        $query = TrainingStudent::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'training_id' => $this->training_id,
            'student_id' => $this->student_id,
            'status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
