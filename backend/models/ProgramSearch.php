<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Program;

/**
 * ProgramSearch represents the model behind the search form about `backend\models\Program`.
 */
class ProgramSearch extends Program
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'days', 'test', 'validation_status', 'status', 'created_by', 'modified_by', 'satker_id'], 'integer'],
            [['number', 'name', 'note', 'stage', 'category', 'validation_note', 'created', 'modified'], 'safe'],
            [['hours'], 'number'],
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
        $query = Program::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
			'satker_id' => $this->satker_id,
            'hours' => $this->hours,
            'days' => $this->days,
            'test' => $this->test,
            'validation_status' => $this->validation_status,
            'status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'stage', $this->stage])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'validation_note', $this->validation_note]);

        return $dataProvider;
    }
}
