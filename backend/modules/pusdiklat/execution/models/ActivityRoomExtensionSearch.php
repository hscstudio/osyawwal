<?php

namespace backend\modules\pusdiklat\execution\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ActivityRoom;

/**
 * ActivityRoomSearch represents the model behind the search form about `backend\models\ActivityRoom`.
 */
class ActivityRoomExtensionSearch extends ActivityRoom
{
     public  
			$startDateX, $endDateX,
			$startTimeX, $endTimeX,
			$location, $computer, $hostel, $capacity;
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['computer', 'hostel','capacity'], 'integer'],
            [['location','startTimeX', 'endTimeX', 'startDateX', 'endDateX'], 'safe'],
			['endDateX',\hscstudio\heart\helpers\DateTimeCompareValidator::className(),'compareAttribute'=>'startDateX','operator'=>'>','message'=>'{attribute} must be greater than {compareValue}.'],
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
    /* public function search($params)
    {
        $query = ActivityRoom::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'activity_id' => $this->activity_id,
            'room_id' => $this->room_id,
            'start' => $this->start,
            'end' => $this->end,
            'status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    } */
}
