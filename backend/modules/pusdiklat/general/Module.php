<?php

namespace backend\modules\pusdiklat\general;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\pusdiklat\general\controllers';

    public function init()
    {
        parent::init();
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			/* ['icon'=>'fa fa-fw fa-link', 'label' => 'Keuangan', 'url' => ['#'], 'items'=>[
			]], */
			['icon'=>'fa fa-fw fa-link', 'label' => '+ Pegawai TU Humas', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-user','label' => 'Manage Person', 'url' => ['person2/index'],'path'=>[
				'/person2/',
				]],
				['icon'=>'fa fa-fw fa-user-md','label' => 'Manage Employee', 'url' => ['employee2/index'],'path'=>[
					'/employee2/',
				]],
				['icon'=>'fa fa-fw fa-key','label' => 'Manage User', 'url' => ['user2/index'],'path'=>[
					'/user2/',
				]],
			]],
			['icon'=>'fa fa-fw fa-link', 'label' => '+ Pengelolaan Aset', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['meeting-activity3/index'],'path'=>[
					'/meeting-activity3/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Room Request', 'url' => ['room-request3/index'],'path'=>[
					'/room-request3/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Room', 'url' => ['room3/index'],'path'=>[
					'/room3/',
				]],
			]]
			/* ['icon'=>'fa fa-fw fa-link', 'label' => 'Aset [75%]', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting', 'url' => ['/'.$this->uniqueId.'/meeting3/index'],'path'=>[
					'meeting3/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Request', 'url' => ['/'.$this->uniqueId.'/meeting-request3/index'],'path'=>[
					'meeting-request3/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Room', 'url' => ['/'.$this->uniqueId.'/room3/index'],'path'=>[
					'room3/',
				]],
			]], */
			/*
			['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training', 'url' => ['/'.$this->uniqueId.'/training/index'],'path'=>'training'],
			['icon'=>'fa fa-fw fa-users', 'label' => 'Employee', 'url' => ['/'.$this->uniqueId.'/employee/index'],'path'=>'employee'],
			['icon'=>'fa fa-fw fa-university', 'label' => 'PIC Satker', 'url' => ['/'.$this->uniqueId.'/satker-pic/index'],'path'=>'satker-pic'],
			*/
		];
	}
}
