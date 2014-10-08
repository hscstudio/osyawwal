<?php

namespace backend\modules\user;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\user\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-user','label' => 'User', 'url' => ['/'.$this->uniqueId.'/user/profile'],'path'=>[
				'/user/',
			],'items'=>[
				['icon'=>'fa fa-fw fa-link','label' => 'Profile', 'url' => ['/'.$this->uniqueId.'/user/profile'],'path'=>[
					'/user/profile',
				]],
				['icon'=>'fa fa-fw fa-key','label' => 'Password', 'url' => ['/'.$this->uniqueId.'/user/password'],'path'=>[
					'/user/password',
				]],
			]],
		];
	}
}
