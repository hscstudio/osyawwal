<?php

namespace backend\modules\sekretariat\hrd;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\admin\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-user','label' => 'Manage Person', 'url' => ['/'.$this->uniqueId.'/person/index'],'path'=>[
				'/person/',
			]],
			['icon'=>'fa fa-fw fa-user','label' => 'Manage Employee', 'url' => ['/'.$this->uniqueId.'/employee/index'],'path'=>[
				'/employee/',
			]],
			['icon'=>'fa fa-fw fa-key','label' => 'Manage User', 'url' => ['/'.$this->uniqueId.'/user/index'],'path'=>[
				'/user/',
			]],
		];
	}
}
