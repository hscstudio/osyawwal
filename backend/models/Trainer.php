<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "trainer".
 *
 * @property integer $person_id
 * @property string $category
 * @property string $education_history
 * @property string $training_history
 * @property string $experience_history
 *
 * @property Person $person
 * @property TrainingClassSubjectTrainerEvaluation[] $trainingClassSubjectTrainerEvaluations
 * @property TrainingScheduleTrainer[] $trainingScheduleTrainers
 * @property TrainingSubjectTrainerRecommendation[] $trainingSubjectTrainerRecommendations
 */
class Trainer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trainer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['person_id'], 'required'],
            [['person_id'], 'integer'],
            [['education_history', 'training_history', 'experience_history'], 'string'],
            [['category'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'person_id' => Yii::t('app', 'Person ID'),
            'category' => Yii::t('app', 'Category'),
            'education_history' => Yii::t('app', 'Education History'),
            'training_history' => Yii::t('app', 'Training History'),
            'experience_history' => Yii::t('app', 'Experience History'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClassSubjectTrainerEvaluations()
    {
        return $this->hasMany(TrainingClassSubjectTrainerEvaluation::className(), ['trainer_id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingScheduleTrainers()
    {
        return $this->hasMany(TrainingScheduleTrainer::className(), ['trainer_id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingSubjectTrainerRecommendations()
    {
        return $this->hasMany(TrainingSubjectTrainerRecommendation::className(), ['trainer_id' => 'person_id']);
    }
}
