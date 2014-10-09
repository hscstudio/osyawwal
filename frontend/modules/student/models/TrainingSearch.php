<?php

namespace frontend\modules\student\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Training;

/**
 * TrainingSearch represents the model behind the search form about `frontend\models\Training`.
 */
class TrainingSearch extends Training
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'program_id', 'program_revision', 'regular', 'student_count_plan', 'class_count_plan', 'approved_status', 'approved_by'], 'integer'],
            [['number', 'note', 'stakeholder', 'execution_sk', 'result_sk', 'cost_source', 'approved_note', 'approved_date'], 'safe'],
            [['cost_plan', 'cost_real'], 'number'],
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
        $query = Training::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'activity_id' => $this->activity_id,
            'program_id' => $this->program_id,
            'program_revision' => $this->program_revision,
            'regular' => $this->regular,
            'student_count_plan' => $this->student_count_plan,
            'class_count_plan' => $this->class_count_plan,
            'cost_plan' => $this->cost_plan,
            'cost_real' => $this->cost_real,
            'approved_status' => $this->approved_status,
            'approved_date' => $this->approved_date,
            'approved_by' => $this->approved_by,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'stakeholder', $this->stakeholder])
            ->andFilterWhere(['like', 'execution_sk', $this->execution_sk])
            ->andFilterWhere(['like', 'result_sk', $this->result_sk])
            ->andFilterWhere(['like', 'cost_source', $this->cost_source])
            ->andFilterWhere(['like', 'approved_note', $this->approved_note]);

        return $dataProvider;
    }
}
