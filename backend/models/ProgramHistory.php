<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "program_history".
 *
 * @property integer $id
 * @property integer $revision
 * @property integer $satker_id
 * @property string $number
 * @property string $name
 * @property string $hours
 * @property integer $days
 * @property integer $test
 * @property string $note
 * @property string $stage
 * @property string $category
 * @property integer $validation_status
 * @property string $validation_note
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 */
class ProgramHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'revision', 'satker_id', 'name'], 'required'],
            [['id', 'revision', 'satker_id', 'days', 'test', 'validation_status', 'status', 'created_by', 'modified_by'], 'integer'],
            [['hours'], 'number'],
            [['created', 'modified'], 'safe'],
            [['number'], 'string', 'max' => 15],
            [['name', 'note', 'stage', 'category', 'validation_note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'revision' => Yii::t('app', 'Revision'),
            'satker_id' => Yii::t('app', 'Satker ID'),
            'number' => Yii::t('app', 'Number'),
            'name' => Yii::t('app', 'Name'),
            'hours' => Yii::t('app', 'Hours'),
            'days' => Yii::t('app', 'Days'),
            'test' => Yii::t('app', 'Test'),
            'note' => Yii::t('app', 'Note'),
            'stage' => Yii::t('app', 'Stage'),
            'category' => Yii::t('app', 'Category'),
            'validation_status' => Yii::t('app', 'Validation Status'),
            'validation_note' => Yii::t('app', 'Validation Note'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified' => Yii::t('app', 'Modified'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
    }
	
	public static function getRevision($id){ 
       return self::find()->where(['id' => $id,])->max('revision'); 
	} 
}
