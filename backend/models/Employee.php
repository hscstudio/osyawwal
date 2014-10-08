<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property integer $person_id
 * @property integer $user_id
 * @property integer $satker_id
 * @property integer $organisation_id
 * @property integer $chairman 
 * @property Person $person
 * @property User $user
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['person_id', 'satker_id'], 'required'],
            [['person_id', 'satker_id', 'user_id', 'organisation_id', 'chairman'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'person_id' => Yii::t('app', 'Person ID'),
            'user_id' => Yii::t('app', 'User ID'),
			'satker_id' => Yii::t('app', 'Satker'),
			'organisation_id' => Yii::t('app', 'Organisation ID'),
            'chairman' => Yii::t('app', 'Chairman'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisation()
    {
        return $this->hasOne(Organisation::className(), ['ID' => 'organisation_id']);
    }
	
	/**
     * @inheritdoc
     * @return ProgramQuery
     */
    public static function find()
    {
        return new EmployeeQuery(get_called_class());
    }
}

class EmployeeQuery extends \yii\db\ActiveQuery
{
    public function currentSatker()
    {
        $this->andWhere(['satker_id'=>(int)Yii::$app->user->identity->employee->satker_id]);
        return $this;
    }
}
