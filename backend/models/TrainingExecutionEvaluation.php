<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_execution_evaluation".
 *
 * @property integer $id
 * @property integer $training_class_student_id
 * @property string $value
 * @property string $text1
 * @property string $text2
 * @property string $text3
 * @property string $text4
 * @property string $text5
 * @property integer $overall
 * @property string $comment
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property TrainingClassStudent $trainingClassStudent
 */
class TrainingExecutionEvaluation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_execution_evaluation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_class_student_id'], 'required'],
            [['training_class_student_id', 'overall', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['value'], 'string', 'max' => 255],
            [['text1', 'text2', 'text3', 'text4', 'text5'], 'string', 'max' => 500],
            [['comment'], 'string', 'max' => 3000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'training_class_student_id' => Yii::t('app', 'Training Class Student ID'),
            'value' => Yii::t('app', 'Value'),
            'text1' => Yii::t('app', 'Text1'),
            'text2' => Yii::t('app', 'Text2'),
            'text3' => Yii::t('app', 'Text3'),
            'text4' => Yii::t('app', 'Text4'),
            'text5' => Yii::t('app', 'Text5'),
            'overall' => Yii::t('app', 'Overall'),
            'comment' => Yii::t('app', 'Comment'),
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
    public function getTrainingClassStudent()
    {
        return $this->hasOne(TrainingClassStudent::className(), ['id' => 'training_class_student_id']);
    }
}
