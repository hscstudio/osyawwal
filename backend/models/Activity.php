<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;								
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use hscstudio\heart\components\HistoryBehavior;
/**
 * This is the model class for table "activity".
 *
 * @property integer $id
 * @property integer $satker_id
 * @property string $name
 * @property string $description
 * @property string $start
 * @property string $end
 * @property string $location
 * @property integer $hostel
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property ActivityRoom[] $activityRooms
 * @property Room[] $rooms
 * @property Meeting $meeting
 * @property Training $training
 */
class Activity extends \yii\db\ActiveRecord
{
	public $create_revision = false;
	/**
     * @inheritdoc
     */	
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created','modified'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'modified',
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_by','modified_by'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_by',
                ],
            ],
			'history' => [
                'class'=>HistoryBehavior::className(),
                'attributes'=>[
					'id',
					'name', 'start', 'end', 'location',
					'satker_id',
					'description',
					'created', 'modified',
					'hostel', 'status', 'created_by', 'modified_by',
					
				],
				'historyClass'=>ActivityHistory::className(),
            ]
        ];
    }
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {			
        return [
            [['name', 'start', 'end'], 'required'],
			[['satker_id'], 'integer'],
            [['description'], 'string'],
            [['start', 'end', 'created', 'modified'], 'safe'],
            [['hostel', 'status', 'created_by', 'modified_by'], 'integer'],
            [['name', 'location'], 'string', 'max' => 255],
            [['name'], 'unique'],
			['end',\hscstudio\heart\helpers\DateTimeCompareValidator::className(),'compareAttribute'=>'start','operator'=>'>=','message'=>'{attribute} must be greater than {compareValue}.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'start' => Yii::t('app', 'Start'),
            'end' => Yii::t('app', 'End'),
            'location' => Yii::t('app', 'Location'),
            'hostel' => Yii::t('app', 'Hostel'),
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
    public function getActivityRooms()
    {
        return $this->hasMany(ActivityRoom::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::className(), ['id' => 'room_id'])->viaTable('{activity_room}', ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeeting()
    {
        return $this->hasOne(Meeting::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTraining()
    {
        return $this->hasOne(Training::className(), ['activity_id' => 'id']);
    }
	
	public function getSatker()
    {
        return $this->hasOne(Reference::className(), ['id' => 'satker_id']);
    }
	
	 /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setSatker($satker='current')
    {
        if($satker=='current'){
			$this->satker_id = Yii::$app->user->identity->employee->satker_id;
		}
    }
	
	/**
     * @inheritdoc
     * @return ProgramQuery
     */
    public static function find()
    {
        return new ActivityQuery(get_called_class());
    }
}

class ActivityQuery extends \yii\db\ActiveQuery
{
    public function currentSatker()
    {
        $this->andWhere(['satker_id'=>(int)Yii::$app->user->identity->employee->satker_id]);
        return $this;
    }
	
	public function active($status=1)
    {
        $this->andWhere(['status'=>$status]);
        return $this;
    }
}
