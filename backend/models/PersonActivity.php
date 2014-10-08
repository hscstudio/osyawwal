<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "person_activity".
 *
 * @property integer $person_id
 * @property integer $activity_id
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 */
class PersonActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['person_id', 'activity_id'], 'required'],
            [['person_id', 'activity_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['activity_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'person_id' => Yii::t('app', 'Person ID'),
            'activity_id' => Yii::t('app', 'Activity ID'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified' => Yii::t('app', 'Modified'),
            'modified_by' => Yii::t('app', 'Modified By'),
        ];
    }
}
