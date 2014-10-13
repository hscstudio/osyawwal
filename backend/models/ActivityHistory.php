<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "activity_history".
 *
 * @property integer $id
 * @property integer $revision
 * @property integer $satker_id
 * @property string $name
 * @property string $description
 * @property string $start
 * @property string $end
 * @property string $location
 * @property integer $hostel
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 */
class ActivityHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description','name', 'location','start', 'end', 'created', 'modified','id', 'revision', 'satker_id', 'hostel', 'status', 'created_by', 'modified_by'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'revision' => Yii::t('app', 'Revision'),
            'satker_id' => Yii::t('app', 'Satker ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'start' => Yii::t('app', 'Start'),
            'end' => Yii::t('app', 'End'),
            'location' => Yii::t('app', 'Location'),
            'hostel' => Yii::t('app', 'Hostel'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified' => Yii::t('app', 'Modified'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
    }
	
	public static function getRevision($id){  
      return self::find()->where(['id' => $id,])->max('revision');  
    } 
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getMeeting()
    {
        return $this->hasOne(Meeting::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTraining()
    {
        return $this->hasOne(Training::className(), ['activity_id' => 'id']);
    }
}
