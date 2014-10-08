<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_schedule_trainer".
 *
 * @property integer $id
 * @property integer $training_schedule_id
 * @property integer $trainer_id
 * @property string $hours
 * @property string $reason
 * @property integer $cost
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property TrainingSchedule $trainingSchedule
 * @property Trainer $trainer
 */
class TrainingScheduleTrainer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_schedule_trainer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_schedule_id', 'trainer_id','type'], 'required'],
            [['training_schedule_id', 'trainer_id', 'cost', 'status', 'created_by', 'modified_by','type'], 'integer'],
            [['hours'], 'number'],
            [['created', 'modified'], 'safe'],
            [['reason'], 'string', 'max' => 255],
            [['training_schedule_id', 'trainer_id'], 'unique', 'targetAttribute' => ['training_schedule_id', 'trainer_id'], 'message' => 'The combination of Training Schedule ID and Trainer ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'training_schedule_id' => Yii::t('app', 'Training Schedule ID'),
			'type' => Yii::t('app', 'Type'),
            'trainer_id' => Yii::t('app', 'Trainer ID'),
            'hours' => Yii::t('app', 'Hours'),
            'reason' => Yii::t('app', 'Reason'),
            'cost' => Yii::t('app', 'Cost'),
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
    public function getTrainingSchedule()
    {
        return $this->hasOne(TrainingSchedule::className(), ['id' => 'training_schedule_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainer()
    {
        return $this->hasOne(Trainer::className(), ['person_id' => 'trainer_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getReference()
    {
        return $this->hasOne(Reference::className(), ['id' => 'type']);
    }
}
