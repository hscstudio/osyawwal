<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "person".
 *
 * @property integer $id
 * @property string $nip
 * @property string $name
 * @property string $nickname
 * @property string $front_title
 * @property string $back_title
 * @property string $nid
 * @property string $npwp
 * @property string $born
 * @property string $birthday
 * @property integer $gender
 * @property string $phone
 * @property string $email
 * @property string $homepage
 * @property string $address
 * @property string $office_phone
 * @property string $office_fax
 * @property string $office_email
 * @property string $office_address
 * @property string $bank_account
 * @property integer $married
 * @property string $photo
 * @property string $blood
 * @property string $graduate_desc
 * @property integer $position
 * @property string $position_desc
 * @property string $organisation
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property Employee $employee
 * @property Student $student
 * @property Trainer $trainer
 */
class Person extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'nid'], 'required'],
            [['birthday', 'created', 'modified'], 'safe'],
            [['gender', 'married', 'position', 'status', 'created_by', 'modified_by'], 'integer'],
            [['nip', 'nickname', 'front_title', 'back_title', 'blood'], 'string', 'max' => 25],
            [['name', 'nid', 'npwp', 'email', 'office_email'], 'string', 'max' => 100],
            [['born', 'phone', 'office_phone', 'office_fax'], 'string', 'max' => 50],
            [['homepage', 'address', 'office_address', 'bank_account', 'graduate_desc', 'position_desc', 'organisation'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nip' => Yii::t('app', 'Nip'),
            'name' => Yii::t('app', 'Name'),
            'nickname' => Yii::t('app', 'Nickname'),
            'front_title' => Yii::t('app', 'Front Title'),
            'back_title' => Yii::t('app', 'Back Title'),
            'nid' => Yii::t('app', 'Nid'),
            'npwp' => Yii::t('app', 'Npwp'),
            'born' => Yii::t('app', 'Born'),
            'birthday' => Yii::t('app', 'Birthday'),
            'gender' => Yii::t('app', 'Gender'),
            'phone' => Yii::t('app', 'Phone'),
            'email' => Yii::t('app', 'Email'),
            'homepage' => Yii::t('app', 'Homepage'),
            'address' => Yii::t('app', 'Address'),
            'office_phone' => Yii::t('app', 'Office Phone'),
            'office_fax' => Yii::t('app', 'Office Fax'),
            'office_email' => Yii::t('app', 'Office Email'),
            'office_address' => Yii::t('app', 'Office Address'),
            'bank_account' => Yii::t('app', 'Bank Account'),
            'married' => Yii::t('app', 'Married'),
            'blood' => Yii::t('app', 'Blood'),
            'graduate_desc' => Yii::t('app', 'Graduate Desc'),
            'position' => Yii::t('app', 'Position'),
            'position_desc' => Yii::t('app', 'Position Desc'),
            'organisation' => Yii::t('app', 'Organisation'),
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
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainer()
    {
        return $this->hasOne(Trainer::className(), ['person_id' => 'id']);
    }
}
