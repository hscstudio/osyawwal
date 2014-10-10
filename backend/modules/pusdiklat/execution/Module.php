<?php

namespace backend\modules\pusdiklat\execution;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\pusdiklat\execution\controllers';

    public function init()
    {
        parent::init();
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-link', 'label' => '+ Execution I', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training', 'url' => ['activity/index'],'path'=>[
					'/activity/',
				]],
				['icon'=>'fa fa-fw fa-user-md', 'label' => 'Student', 'url' => ['student/index'],'path'=>[
					'/student/',
				]],
			]],
			['icon'=>'fa fa-fw fa-link', 'label' => '+ Execution II', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training', 'url' => ['activity2/index'],'path'=>[
					'/activity2/',
					'/training-class-student-attendance/',
					'/training-schedule-trainer-attendance/'
				]],
				['icon'=>'fa fa-fw fa-user-md', 'label' => 'Student', 'url' => ['student2/index'],'path'=>[
					'/student2/',
				]],
			]],
		];
	}
}
