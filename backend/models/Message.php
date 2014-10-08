<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $category
 * @property integer $author
 * @property integer $recipient
 * @property string $subject
 * @property string $content
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'category', 'author', 'recipient', 'content'], 'required'],
            [['type', 'category', 'author', 'recipient', 'status', 'created_by'], 'integer'],
            [['content'], 'string'],
            [['created'], 'safe'],
            [['subject'], 'string', 'max' => 255],
            [['type'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'category' => Yii::t('app', 'Category'),
            'author' => Yii::t('app', 'Author'),
            'recipient' => Yii::t('app', 'Recipient'),
            'subject' => Yii::t('app', 'Subject'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }
}
