<?php

namespace backend\models;

use Yii;
use hscstudio\heart\components\HistoryBehavior;
/**
 * This is the model class for table "training".
 *
 * @property integer $activity_id
 * @property integer $program_id
 * @property integer $program_revision
 * @property string $number
 * @property string $note
 * @property integer $regular
 * @property string $stakeholder
 * @property integer $student_count_plan
 * @property integer $class_count_plan
 * @property string $execution_sk
 * @property string $result_sk
 * @property string $cost_source
 * @property string $cost_plan
 * @property string $cost_real
 * @property integer $approved_status
 * @property string $approved_note
 * @property string $approved_date
 * @property integer $approved_by
 *
 * @property Activity $activity
 * @property Program $program
 * @property TrainingClass[] $trainingClasses
 * @property TrainingClassStudent[] $trainingClassStudents
 * @property TrainingStudent[] $trainingStudents
 * @property TrainingStudentPlan $trainingStudentPlan
 * @property TrainingSubjectTrainerRecommendation[] $trainingSubjectTrainerRecommendations
 */
class Training extends \yii\db\ActiveRecord
{
    public $create_revision = false;
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training';
    }

	 public function behaviors()
    {
        return [
			'history' => [
                'class'=>HistoryBehavior::className(),
                'attributes'=>[
					'activity_id', 'program_id', 'program_revision', 'regular', 'student_count_plan', 'class_count_plan', 'approved_status', 'approved_by',
					'cost_plan', 'cost_real','approved_date','number','note', 'stakeholder', 'execution_sk', 'result_sk', 'cost_source', 'approved_note',				
				],
				'historyClass'=>TrainingHistory::className(),
				'primaryField'=>'activity_id',
            ]
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'program_id'], 'required'],
            [['activity_id', 'program_id', 'program_revision', 'regular', 'student_count_plan', 'class_count_plan', 'approved_status', 'approved_by'], 'integer'],
            [['cost_plan', 'cost_real'], 'number'],
            [['approved_date'], 'safe'],
            [['number'], 'string', 'max' => 100],
            [['note', 'stakeholder', 'execution_sk', 'result_sk', 'cost_source', 'approved_note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_id' => Yii::t('app', 'Activity ID'),
            'program_id' => Yii::t('app', 'Program ID'),
            'program_revision' => Yii::t('app', 'Program Revision'),
            'number' => Yii::t('app', 'Number'),
            'note' => Yii::t('app', 'Note'),
            'regular' => Yii::t('app', 'Regular'),
            'stakeholder' => Yii::t('app', 'Stakeholder'),
            'student_count_plan' => Yii::t('app', 'Student Count Plan'),
            'class_count_plan' => Yii::t('app', 'Class Count Plan'),
            'execution_sk' => Yii::t('app', 'Execution Sk'),
            'result_sk' => Yii::t('app', 'Result Sk'),
            'cost_source' => Yii::t('app', 'Cost Source'),
            'cost_plan' => Yii::t('app', 'Cost Plan'),
            'cost_real' => Yii::t('app', 'Cost Real'),
            'approved_status' => Yii::t('app', 'Approved Status'),
            'approved_note' => Yii::t('app', 'Approved Note'),
            'approved_date' => Yii::t('app', 'Approved Date'),
            'approved_by' => Yii::t('app', 'Approved By'),
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
    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClasses()
    {
        return $this->hasMany(TrainingClass::className(), ['training_id' => 'activity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClassStudents()
    {
        return $this->hasMany(TrainingClassStudent::className(), ['training_id' => 'activity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingStudents()
    {
        return $this->hasMany(TrainingStudent::className(), ['training_id' => 'activity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingStudentPlan()
    {
        return $this->hasOne(TrainingStudentPlan::className(), ['training_id' => 'activity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingSubjectTrainerRecommendations()
    {
        return $this->hasMany(TrainingSubjectTrainerRecommendation::className(), ['training_id' => 'activity_id']);
    }
}
