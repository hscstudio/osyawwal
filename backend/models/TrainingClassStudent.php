<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_class_student".
 *
 * @property integer $id
 * @property integer $training_id
 * @property integer $training_class_id
 * @property integer $training_student_id
 * @property string $number
 * @property integer $head_class
 * @property string $activity
 * @property string $presence
 * @property string $pre_test
 * @property string $post_test
 * @property string $test
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property TrainingStudent $trainingStudent
 * @property Training $training
 * @property TrainingClass $trainingClass
 * @property TrainingClassStudentAttendance[] $trainingClassStudentAttendances
 * @property TrainingExecutionEvaluation[] $trainingExecutionEvaluations
 */
class TrainingClassStudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_class_student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_id', 'training_class_id', 'training_student_id'], 'required'],
            [['training_id', 'training_class_id', 'training_student_id', 'head_class', 'status', 'created_by', 'modified_by'], 'integer'],
            [['activity', 'presence', 'pre_test', 'post_test', 'test'], 'number'],
            [['created', 'modified'], 'safe'],
            [['number'], 'string', 'max' => 255],
            [['training_id', 'training_student_id'], 'unique', 'targetAttribute' => ['training_id', 'training_student_id'], 'message' => 'The combination of Training ID and Training Student ID has already been taken.']
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
            'training_class_id' => Yii::t('app', 'Training Class ID'),
            'training_student_id' => Yii::t('app', 'Training Student ID'),
            'number' => Yii::t('app', 'Number'),
            'head_class' => Yii::t('app', 'Head Class'),
            'activity' => Yii::t('app', 'Activity'),
            'presence' => Yii::t('app', 'Presence'),
            'pre_test' => Yii::t('app', 'Pre Test'),
            'post_test' => Yii::t('app', 'Post Test'),
            'test' => Yii::t('app', 'Test'),
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
    public function getTrainingStudent()
    {
        return $this->hasOne(TrainingStudent::className(), ['id' => 'training_student_id']);
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
    public function getTrainingClass()
    {
        return $this->hasOne(TrainingClass::className(), ['id' => 'training_class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClassStudentAttendances()
    {
        return $this->hasMany(TrainingClassStudentAttendance::className(), ['training_class_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingExecutionEvaluations()
    {
        return $this->hasMany(TrainingExecutionEvaluation::className(), ['training_class_student_id' => 'id']);
    }
}
