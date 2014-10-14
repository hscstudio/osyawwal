<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "meeting".
 *
 * @property integer $activity_id
 * @property integer $attendance_count_plan
 * @property integer $organisation_id
 *
 * @property Activity $activity
 */
class Meeting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'meeting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id'], 'required'],
            [['activity_id', 'attendance_count_plan', 'organisation_id', ], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_id' => Yii::t('app', 'Activity ID'),
            'attendance_count_plan' => Yii::t('app', 'Attendance Count Plan'),
			'organisation_id' => Yii::t('app', 'Organisation ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisation()
    {
        return $this->hasOne(Organisation::className(), ['ID' => 'organisation_id']);
    }
}
