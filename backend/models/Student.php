<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "student".
 *
 * @property integer $person_id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string $eselon2
 * @property string $eselon3
 * @property string $eselon4
 * @property integer $satker
 * @property integer $no_sk
 * @property integer $tmt_sk
 *
 * @property Person $person
 * @property TrainingClassSubjectTrainerEvaluation[] $trainingClassSubjectTrainerEvaluations
 * @property TrainingStudent[] $trainingStudents
 */
class Student extends \yii\db\ActiveRecord
{
    public $new_password;
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['person_id'], 'required'],
            [['person_id', 'satker', 'no_sk', 'tmt_sk'], 'integer'],
            [['username'], 'string', 'max' => 25],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
			['new_password', 'string', 'min' => 6],
            [['eselon2', 'eselon3', 'eselon4'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'person_id' => Yii::t('app', 'Person ID'),
            'username' => Yii::t('app', 'Username'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'eselon2' => Yii::t('app', 'Eselon2'),
            'eselon3' => Yii::t('app', 'Eselon3'),
            'eselon4' => Yii::t('app', 'Eselon4'),
            'satker' => Yii::t('app', 'Satker'),
            'no_sk' => Yii::t('app', 'No Sk'),
            'tmt_sk' => Yii::t('app', 'Tmt Sk'),
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
    public function getTrainingClassSubjectTrainerEvaluations()
    {
        return $this->hasMany(TrainingClassSubjectTrainerEvaluation::className(), ['student_id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingStudents()
    {
        return $this->hasMany(TrainingStudent::className(), ['student_id' => 'person_id']);
    }
	
	/**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
}
