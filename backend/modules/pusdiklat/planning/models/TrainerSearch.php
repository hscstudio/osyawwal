<?php

namespace backend\modules\pusdiklat\planning\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Trainer;

/**
 * TrainerSearch represents the model behind the search form about `backend\models\Trainer`.
 */
class TrainerSearch extends Trainer
{
	public $name,$phone,$organisation, $nid, $nip;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['person_id'], 'integer'],
            [['category', 'education_history', 'training_history', 'experience_history'], 'safe'],
			[['name', 'phone', 'organisation', 'nid', 'nip'], 'safe']
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
        $query = Trainer::find()
			->joinWith('person');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'person_id' => $this->person_id,
        ]);

        $query->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'education_history', $this->education_history])
            ->andFilterWhere(['like', 'training_history', $this->training_history])
            ->andFilterWhere(['like', 'experience_history', $this->experience_history])
			
			->andFilterWhere(['like', 'person.name', $this->name])
			->andFilterWhere(['like', 'person.phone', $this->phone])
			->andFilterWhere(['like', 'person.organisation', $this->organisation])
			->andFilterWhere(['like', 'person.nid', $this->nid])
			->andFilterWhere(['like', 'person.nip', $this->nip])
			;

        return $dataProvider;
    }
}
