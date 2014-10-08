<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "reference".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $sort
 * @property integer $status 
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property ObjectReference[] $objectReferences
 */
class Reference extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reference';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'status', 'created_by', 'modified_by', 'sort'], 'integer'],
            [['name'], 'required'],
            [['created', 'modified'], 'safe'],
            [['name', 'value','type'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
			'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
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
    public function getObjectReferences()
    {
        return $this->hasMany(ObjectReference::className(), ['object_id' => 'id']);
    }
}
