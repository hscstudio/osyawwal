<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log','heart'],
	'homeUrl' => '/github/osyawwal/administrator',
    'modules' => [
		'heart' => [
            'class' => 'hscstudio\heart\Module',
            'features'=>[
                // datecontrol (kartik), gridview (kartik), gii, 
                'datecontrol'=>true,// use false for not use it
                'gridview'=>true,// use false for not use it
                'gii'=>true, // use false for not use it
                'privilege'=>[
					'allowActions' => [
						/* DEFAULT */
						'debug/*',
						'site/*',
						'gii/*',						
						'privilege/*',
						'gridview/*',	// add or remove allowed actions to this list
						'file/*',
						/* DEFAULT */
						'user/*',						
						/* ADMIN */
						'admin/*',
						/* ADMIN */
						/* START SEKRETARIAT */
						'sekretariat-organisation/*',
						'sekretariat-hrd/*',
						'sekretariat-finance/*',
						'sekretariat-it/*',
						'sekretariat-general/*',
						/* FINISH SEKRETARIAT */
						/* START PUSDIKLAT */
						'pusdiklat-general/*',
						'pusdiklat-planning/*',
						'pusdiklat-execution/*',
						'pusdiklat-evaluation/*',
						/* FINISH PUSDIKLAT */
						/* START PUSDIKLAT2 */
						'pusdiklat2-general/*',
						'pusdiklat2-training/*',
						'pusdiklat2-test/*',
						'pusdiklat2-scholarship/*',
						/* FINISH PUSDIKLAT2 */
						/* START BDK */
						'bdk-general/*',
						'bdk-execution/*' ,
						'bdk-evaluation/*' ,
						/* FINISH BDK */
					],
					'authManager' => [
					  'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\PhpManager'
					]
				],
            ],
        ],
		'admin' => [
            'class' => 'backend\modules\admin\Module',
        ],
		'user' => [
            'class' => 'backend\modules\user\Module',
        ],
		/* START PUSDIKLAT */
		'pusdiklat-general' => [
            'class' => 'backend\modules\pusdiklat\general\Module',
        ],
		'pusdiklat-planning' => [
            'class' => 'backend\modules\pusdiklat\planning\Module',
        ],
		'pusdiklat-execution' => [
            'class' => 'backend\modules\pusdiklat\execution\Module',
        ],
		'pusdiklat-evaluation' => [
            'class' => 'backend\modules\pusdiklat\evaluation\Module',
        ],
		/* FINISH PUSDIKLAT */
	],
    'components' => [
        'user' => [
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
			'identityCookie' => [
			  'name' => '_backendUser', // unique for backend
			  'path'=>'c:\xampp\htdocs\github\osyawwal\backend\web'  // correct path for the backend app.
			]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		// http://mickgeek.com/yii-2-advanced-template-on-the-same-domain
		'request' => [
            'baseUrl' => '/github/osyawwal/administrator',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'suffix' => '.aspx',
			/* 'rules' => [
				['class' => 'hscstudio\heart\helpers\UrlRule', 'connectionID' => 'db',],
			], */
        ],		
    ],
    'params' => $params,
];
