<?php

namespace frontend\modules\student;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\student\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-user','label' => 'Student', 'url' => ['/'.$this->uniqueId.'/student/profile'],'path'=>[
				'/student/',
			],'items'=>[
				['icon'=>'fa fa-fw fa-link','label' => 'Profile', 'url' => ['/'.$this->uniqueId.'/student/profile'],'path'=>[
					'/student/profile',
				]],
				['icon'=>'fa fa-fw fa-key','label' => 'Password', 'url' => ['/'.$this->uniqueId.'/student/password'],'path'=>[
					'/student/password',
				]],
				['icon'=>'fa fa-fw fa-key','label' => 'Training', 'url' => ['/'.$this->uniqueId.'/activity/index'],'path'=>[
					'/activity',
				]],
			]],
		];
	}
}
