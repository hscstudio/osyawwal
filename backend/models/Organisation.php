<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "organisation".
 *
 * @property integer $ID
 * @property string $KD_UNIT_ORG
 * @property string $KD_UNIT_ES1
 * @property string $KD_UNIT_ES2
 * @property string $KD_UNIT_ES3
 * @property string $KD_UNIT_ES4
 * @property string $KD_UNIT_ES5
 * @property integer $JNS_KANTOR
 * @property string $NM_UNIT_ORG
 * @property string $KD_ESELON
 * @property string $KD_SURAT_ORG
 * @property string $TKT_ESELON
 */
class Organisation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organisation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KD_UNIT_ORG', 'KD_UNIT_ES1', 'KD_UNIT_ES2', 'KD_UNIT_ES3', 'KD_UNIT_ES4', 'KD_UNIT_ES5', 'JNS_KANTOR', 'NM_UNIT_ORG', 'KD_ESELON', 'KD_SURAT_ORG', 'TKT_ESELON'], 'required'],
            [['JNS_KANTOR'], 'integer'],
            [['KD_UNIT_ORG'], 'string', 'max' => 10],
            [['KD_UNIT_ES1', 'KD_UNIT_ES2', 'KD_UNIT_ES3', 'KD_UNIT_ES4', 'KD_UNIT_ES5', 'KD_ESELON', 'TKT_ESELON'], 'string', 'max' => 2],
            [['NM_UNIT_ORG', 'KD_SURAT_ORG'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'KD_UNIT_ORG' => Yii::t('app', 'Kd  Unit  Org'),
            'KD_UNIT_ES1' => Yii::t('app', 'Kd  Unit  Es1'),
            'KD_UNIT_ES2' => Yii::t('app', 'Kd  Unit  Es2'),
            'KD_UNIT_ES3' => Yii::t('app', 'Kd  Unit  Es3'),
            'KD_UNIT_ES4' => Yii::t('app', 'Kd  Unit  Es4'),
            'KD_UNIT_ES5' => Yii::t('app', 'Kd  Unit  Es5'),
            'JNS_KANTOR' => Yii::t('app', 'Jns  Kantor'),
            'NM_UNIT_ORG' => Yii::t('app', 'Nm  Unit  Org'),
            'KD_ESELON' => Yii::t('app', 'Kd  Eselon'),
            'KD_SURAT_ORG' => Yii::t('app', 'Kd  Surat  Org'),
            'TKT_ESELON' => Yii::t('app', 'Tkt  Eselon'),
        ];
    }
}
