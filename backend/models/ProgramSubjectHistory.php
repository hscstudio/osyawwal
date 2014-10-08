<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "program_subject_history".
 *
 * @property integer $id
 * @property integer $program_subject_id
 * @property integer $program_id
 * @property integer $program_revision
 * @property string $type
 * @property string $name
 * @property string $hours
 * @property integer $sort
 * @property integer $test
 * @property integer $stage
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 */
class ProgramSubjectHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program_subject_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_subject_id', 'program_id', 'name', 'hours'], 'required'],
            [['type','program_subject_id', 'program_id', 'program_revision', 'sort', 'test', 'status', 'created_by', 'modified_by'], 'integer'],
            [['hours'], 'number'],
            [['created', 'modified','stage'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'program_subject_id' => Yii::t('app', 'Program Subject ID'),
            'program_id' => Yii::t('app', 'Program ID'),
            'program_revision' => Yii::t('app', 'Program Revision'),
            'name' => Yii::t('app', 'Name'),
            'hours' => Yii::t('app', 'Hours'),
            'sort' => Yii::t('app', 'Sort'),
            'test' => Yii::t('app', 'Test'),
			'stage' => Yii::t('app', 'Stage'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified' => Yii::t('app', 'Modified'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
    }
}
