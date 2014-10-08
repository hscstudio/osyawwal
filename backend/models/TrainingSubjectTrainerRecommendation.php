<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_subject_trainer_recommendation".
 *
 * @property integer $id
 * @property integer $training_id
 * @property integer $program_subject_id
 * @property integer $type
 * @property integer $trainer_id
 * @property string $note
 * @property integer $sort
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property Training $training
 * @property Trainer $trainer
 */
class TrainingSubjectTrainerRecommendation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_subject_trainer_recommendation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_id', 'program_subject_id', 'type', 'trainer_id'], 'required'],
            [['training_id', 'program_subject_id', 'type', 'trainer_id', 'sort', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'training_id' => Yii::t('app', 'Training ID'),
            'program_subject_id' => Yii::t('app', 'Program Subject ID'),
            'type' => Yii::t('app', 'Type'),
            'trainer_id' => Yii::t('app', 'Trainer ID'),
            'note' => Yii::t('app', 'Note'),
            'sort' => Yii::t('app', 'Sort'),
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
    public function getTraining()
    {
        return $this->hasOne(Training::className(), ['activity_id' => 'training_id']);
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
