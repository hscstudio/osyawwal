<?php

namespace backend\modules\sekretariat\organisation;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\sekretariat\organisation\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-tree','label' => 'Referensi', 'url' => '#','items'=>[
			['icon'=>'fa fa-fw fa-graduation-cap','label' => 'Graduate', 'url' => ['/'.$this->uniqueId.'/reference-graduate/index'],'path'=>'reference-graduate/'],
			['icon'=>'fa fa-fw fa-sliders','label' => 'Program Code', 'url' => ['/'.$this->uniqueId.'/reference-program-code/index'],'path'=>'reference-program-code/'],
			['icon'=>'fa fa-fw fa-empire', 'label' => 'Religion', 'url' => ['/'.$this->uniqueId.'/reference-religion/index'],'path'=>'reference-religion/'],
			['icon'=>'fa fa-fw fa-trophy', 'label' => 'RankClass', 'url' => ['/'.$this->uniqueId.'/reference-rank-class/index'],'path'=>'reference-rank-class/'],
			['icon'=>'fa fa-fw fa-institution', 'label' => 'Satker', 'url' => ['/'.$this->uniqueId.'/reference-satker/index'],'path'=>'reference-satker/'],
			['icon'=>'fa fa-fw fa-building', 'label' => 'Unit', 'url' => ['/'.$this->uniqueId.'/reference-unit/index'],'path'=>'reference-unit/'],	
			]],
		];
	}
}
