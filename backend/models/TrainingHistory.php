<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_history".
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
 */
class TrainingHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'program_id','revision'], 'required'],
            [['revision','activity_id', 'program_id', 'program_revision', 'regular', 'student_count_plan', 'class_count_plan', 'approved_status', 'approved_by'], 'integer'],
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
	
	public static function getRevision($id){  
      return self::find()->where(['activity_id' => $id,])->max('revision');  
    } 
}
