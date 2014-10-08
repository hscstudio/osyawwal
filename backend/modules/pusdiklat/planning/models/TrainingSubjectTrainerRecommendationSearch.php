<?php

namespace backend\modules\pusdiklat\planning\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TrainingSubjectTrainerRecommendation;

/**
 * TrainingSubjectTrainerRecommendationSearch represents the model behind the search form about `backend\models\TrainingSubjectTrainerRecommendation`.
 */
class TrainingSubjectTrainerRecommendationSearch extends TrainingSubjectTrainerRecommendation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'training_id', 'program_subject_id', 'type', 'trainer_id', 'sort', 'status', 'created_by', 'modified_by'], 'integer'],
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
        $query = TrainingSubjectTrainerRecommendation::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'training_id' => $this->training_id,
            'program_subject_id' => $this->program_subject_id,
            'type' => $this->type,
            'trainer_id' => $this->trainer_id,
            'sort' => $this->sort,
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
