<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Person;

/**
 * PersonSearch represents the model behind the search form about `backend\models\Person`.
 */
class PersonSearch extends Person
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gender', 'married', 'position', 'status', 'created_by', 'modified_by'], 'integer'],
            [['nip', 'name', 'nickname', 'front_title', 'back_title', 'nid', 'npwp', 'born', 'birthday', 'phone', 'email', 'homepage', 'address', 'office_phone', 'office_fax', 'office_email', 'office_address', 'bank_account', 'blood', 'graduate_desc', 'position_desc', 'organisation', 'created', 'modified'], 'safe'],
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
        $query = Person::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'married' => $this->married,
            'position' => $this->position,
            'status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'nip', $this->nip])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'front_title', $this->front_title])
            ->andFilterWhere(['like', 'back_title', $this->back_title])
            ->andFilterWhere(['like', 'nid', $this->nid])
            ->andFilterWhere(['like', 'npwp', $this->npwp])
            ->andFilterWhere(['like', 'born', $this->born])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'homepage', $this->homepage])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'office_phone', $this->office_phone])
            ->andFilterWhere(['like', 'office_fax', $this->office_fax])
            ->andFilterWhere(['like', 'office_email', $this->office_email])
            ->andFilterWhere(['like', 'office_address', $this->office_address])
            ->andFilterWhere(['like', 'bank_account', $this->bank_account])
            ->andFilterWhere(['like', 'blood', $this->blood])
            ->andFilterWhere(['like', 'graduate_desc', $this->graduate_desc])
            ->andFilterWhere(['like', 'position_desc', $this->position_desc])
            ->andFilterWhere(['like', 'organisation', $this->organisation]);

        return $dataProvider;
    }
}
