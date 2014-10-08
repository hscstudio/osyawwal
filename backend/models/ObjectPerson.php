<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "object_person".
 *
 * @property string $object
 * @property integer $object_id
 * @property string $type
 * @property integer $person_id
 *
 * @property Person $person
 */
class ObjectPerson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object', 'object_id', 'type', 'person_id'], 'required'],
            [['object_id', 'person_id'], 'integer'],
            [['object'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'object' => Yii::t('app', 'Object'),
            'object_id' => Yii::t('app', 'Object ID'),
            'type' => Yii::t('app', 'Type'),
            'person_id' => Yii::t('app', 'Person ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }
}
