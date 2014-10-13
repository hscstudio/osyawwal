<?php

namespace backend\modules\pusdiklat\planning\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProgramHistory;

/**
 * ProgramSearch represents the model behind the search form about `backend\models\Program`.
 */
class ProgramHistorySearch extends ProgramHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'revision', 'days', 'test', 'validation_status', 'status', 'created_by', 'modified_by', 'satker_id'], 'integer'],
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
		$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
        $query = ProgramHistory::find()
			->where([
				'satker_id' => $satker_id,
			]);
			
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
			'revision' => $this->revision,
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
