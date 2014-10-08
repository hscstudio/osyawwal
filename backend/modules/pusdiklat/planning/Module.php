<?php

namespace backend\modules\pusdiklat\planning;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\pusdiklat\planning\controllers';

    public function init()
    {
        parent::init();
    }
	
	public function getMenuItems(){
		$callback = function($menuX){
			$data = eval($menuX['data']);
			return [
				'label' => $menuX['name'], 
				'url' => [$menuX['route']],
				'icon'=> isset($data['icon'])?$data['icon']:'',
				'path'=> isset($data['path'])?$data['path']:'',
				'items' => $menuX['children'],
			];
		};
		
		$menus = (\hscstudio\heart\modules\admin\components\AccessHelper::getAssignedMenu(Yii::$app->user->id,4,$callback,true));
		//return $menus;
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-code-fork', 'label' => '+ Program', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-list','label' => 'Program', 'url' => ['program/index'],'path'=>[
					'/program/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Training Activity', 'url' => ['activity/index'],'path'=>[
					'/activity/',
				]],
			]],
			['icon'=>'fa fa-fw fa-university', 'label' => '+ Kurikulum', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-list','label' => 'Program', 'url' => ['program2/index'],'path'=>[
					'/program2/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Training Activity', 'url' => ['activity2/index'],'path'=>[
					'/activity2/',
				]],
			]],
			['icon'=>'fa fa-fw fa-users', 'label' => '+ Tenaga Pengajar', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-list','label' => 'Program', 'url' => ['program3/index'],'path'=>[
					'/program3/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Training Activity', 'url' => ['activity3/index'],'path'=>[
					'/activity3/',
				]],
				['icon'=>'fa fa-fw fa-stack-exchange', 'label' => 'Trainer', 'url' => ['trainer3/index'],'path'=>[
					'/trainer3/',
				]],
			]],
			/* 
			['icon'=>'fa fa-fw fa-users', 'label' => 'Tenaga Pengajar [75%]', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-list','label' => 'Program', 'url' => [program3/index'],'path'=>[
					'program3/','program-subject3/','program-document3/','program-history3/','program-subject-document3/','program-subject-history3/'
				]],
				['icon'=>'fa fa-fw fa-stack-exchange', 'label' => 'Training', 'url' => [training3/index'],'path'=>[
					'training3/','training-history3/','training-subject3/','training-subject-trainer-recommendation3/',
				]],
				
			]],
			['icon'=>'fa fa-fw fa-university', 'label' => 'Testing', 'url' => [testing/index'],'path'=>[
					'testing/',
				]], */
		];
		
		/*
		return [
			'icon'=>'fa fa-list fa-fw',
			'path'=>[
				'program/','program-subject/','program-document/','program-history/','program-subject-document/','program-subject-history/'
			]
		];
		*/
	}
}
