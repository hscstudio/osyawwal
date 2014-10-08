<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "student".
 *
 * @property integer $person_id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string $password_reset_token
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
class Student extends \yii\db\ActiveRecord  implements IdentityInterface
{
    const STATUS_ACTIVE = 1;
	public $new_password,$old_password;
	
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
            [['person_id','username'], 'required'],
            [['person_id', 'satker', 'no_sk', 'tmt_sk'], 'integer'],
            [['username'], 'string', 'max' => 25],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['password_reset_token', 'eselon2', 'eselon3', 'eselon4'], 'string', 'max' => 255],
			['status', 'default', 'value' => self::STATUS_ACTIVE],
			['new_password', 'string', 'min' => 6],
			
			// username, email and password are all required in "register" scenario
			// [['username', 'email', 'new_password'], 'required', 'on' => 'register'],

			// username and password are required in "login" scenario
			[['old_password', 'new_password'], 'required', 'on' => 'password'],
        ];
    }
	
	public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['password'] = ['old_password','new_password'];
		$scenarios['profile'] = ['satker', 'no_sk', 'tmt_sk','eselon2', 'eselon3', 'eselon4'];
        return $scenarios;
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
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'eselon2' => Yii::t('app', 'Eselon2'),
            'eselon3' => Yii::t('app', 'Eselon3'),
            'eselon4' => Yii::t('app', 'Eselon4'),
            'satker' => Yii::t('app', 'Satker'),
            'no_sk' => Yii::t('app', 'No Sk'),
            'tmt_sk' => Yii::t('app', 'Tmt Sk'),
			'status' => Yii::t('app', 'Status'),
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
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['person_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne([
			'username' => $username, 
			'status' => self::STATUS_ACTIVE
		]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
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

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
