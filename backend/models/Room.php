<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "room".
 *
 * @property integer $id
 * @property integer $satker_id
 * @property string $code
 * @property string $name
 * @property integer $capacity
 * @property integer $owner
 * @property integer $computer
 * @property integer $hostel
 * @property string $address
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property ActivityRoom[] $activityRooms
 * @property Activity[] $activities
 */
class Room extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['capacity', 'owner', 'computer', 'hostel', 'status', 'created_by', 'modified_by', 'satker_id'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['code'], 'string', 'max' => 25],
            [['name', 'address'], 'string', 'max' => 255],
            [['code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
			'satker_id' => Yii::t('app', 'Satker'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'capacity' => Yii::t('app', 'Capacity'),
            'owner' => Yii::t('app', 'Owner'),
            'computer' => Yii::t('app', 'Computer'),
            'hostel' => Yii::t('app', 'Hostel'),
            'address' => Yii::t('app', 'Address'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified' => Yii::t('app', 'Modified'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityRooms()
    {
        return $this->hasMany(ActivityRoom::className(), ['room_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['id' => 'activity_id'])->viaTable('{activity_room}', ['room_id' => 'id']);
    }
}
