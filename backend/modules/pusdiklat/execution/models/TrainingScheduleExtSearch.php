<?php

namespace backend\modules\pusdiklat\execution\models;

use Yii;
use yii\base\Model;

/**
 * TrainingScheduleSearch represents the model behind the search form about `backend\models\TrainingSchedule`.
 */
class TrainingScheduleExtSearch  extends Model // extends TrainingSchedule
{
    public  $hours, $minutes, $training_class_subject_id, 
			$startDate, $startTime,
			$activity, $pic, $scheduleDate,
			$activity_room_id;
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['hours'], 'number'],
			[['training_class_subject_id','minutes','activity_room_id'], 'integer'],
			[['activity', 'pic', 'startTime', 'startDate', 'scheduleDate'], 'safe'],
        ];
    }

     public function attributeLabels()
    {
        return [
            'training_class_subject_id' => 'Training Class Subject',
            'activity' => 'Other Activity',
            'pic' => 'Pic',
            'hours' => 'In Hours (JP)',
			'minutes' => 'In Minutes',
            'startTime' => 'Start Time',
            'startDate' => 'Start Date',
			'scheduleDate' => 'Schedule Date',
			'activity_room_id'=> 'Activity Room',
        ];
    }
}
