<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_class_student_certificate".
 *
 * @property integer $training_class_student_id
 * @property string $number
 * @property string $seri
 * @property string $date
 * @property integer $position
 * @property string $position_desc
 * @property integer $graduate
 * @property string $graduate_desc
 * @property string $eselon2
 * @property string $eselon3
 * @property string $eselon4
 * @property string $satker
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 */
class TrainingClassStudentCertificate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_class_student_certificate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_class_student_id'], 'required'],
            [['training_class_student_id', 'position', 'graduate', 'status', 'created_by', 'modified_by'], 'integer'],
            [['date', 'created', 'modified'], 'safe'],
            [['satker'], 'string'],
            [['number', 'seri'], 'string', 'max' => 50],
            [['position_desc', 'graduate_desc', 'eselon2', 'eselon3', 'eselon4'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'training_class_student_id' => Yii::t('app', 'Training Class Student ID'),
            'number' => Yii::t('app', 'Number'),
            'seri' => Yii::t('app', 'Seri'),
            'date' => Yii::t('app', 'Date'),
            'position' => Yii::t('app', 'Position'),
            'position_desc' => Yii::t('app', 'Position Desc'),
            'graduate' => Yii::t('app', 'Graduate'),
            'graduate_desc' => Yii::t('app', 'Graduate Desc'),
            'eselon2' => Yii::t('app', 'Eselon2'),
            'eselon3' => Yii::t('app', 'Eselon3'),
            'eselon4' => Yii::t('app', 'Eselon4'),
            'satker' => Yii::t('app', 'Satker'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified' => Yii::t('app', 'Modified'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
    }
}
