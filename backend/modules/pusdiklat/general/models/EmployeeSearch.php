<?php

namespace backend\modules\pusdiklat\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form about `backend\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['person_id', 'user_id', 'satker_id'], 'integer'],
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
        $query = Employee::find()
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
            'person_id' => $this->person_id,
            'user_id' => $this->user_id,
			
        ]);

        return $dataProvider;
    }
}
