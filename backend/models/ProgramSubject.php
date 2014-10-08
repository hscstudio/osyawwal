<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "program_subject".
 *
 * @property integer $id
 * @property integer $program_id
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
 *
 * @property Program $program
 */
class ProgramSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'name', 'hours','type'], 'required'],
            [['type','program_id', 'sort', 'test', 'status', 'created_by', 'modified_by'], 'integer'],
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
            'program_id' => Yii::t('app', 'Program ID'),
            'type' => Yii::t('app', 'Type'),
			'name' => Yii::t('app', 'Name'),
            'hours' => Yii::t('app', 'Hours'),
            'sort' => Yii::t('app', 'Sort'),
            'test' => Yii::t('app', 'Test'),
            'status' => Yii::t('app', 'Status'),
			'stage' => Yii::t('app', 'Stage'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified' => Yii::t('app', 'Modified'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
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
    public function getReference()
    {
        return $this->hasOne(Reference::className(), ['id' => 'type']);
    }
	
}
