<?php

namespace backend\modules\pusdiklat\execution\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TrainingSchedule;

/**
 * TrainingScheduleSearch represents the model behind the search form about `backend\models\TrainingSchedule`.
 */
class TrainingScheduleSearch extends TrainingSchedule
{
	public $startDate, $endDate;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'training_class_id', 'training_class_subject_id', 'activity_room_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['activity', 'pic', 'start', 'end', 'created', 'modified','startDate', 'endDate'], 'safe'],
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
        $query = TrainingSchedule::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'training_class_id' => $this->training_class_id,
            'training_class_subject_id' => $this->training_class_subject_id,
            'activity_room_id' => $this->activity_room_id,
            'activity' => $this->activity,
            'pic' => $this->pic,
            'hours' => $this->hours,
            'start' => $this->start,
            'end' => $this->end,
            'status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'activity', $this->activity])
            ->andFilterWhere(['like', 'pic', $this->pic]);
		
		if(!empty($this->startDate)){
			$query->andFilterWhere(['>=', 'date(start)', $this->startDate]);
		}
		
		if(!empty($this->endDate)){
            $query->andFilterWhere(['<=', 'date(end)', $this->endDate]);
		}
        return $dataProvider;
    }
}

