<?php
use yii\helpers\Html;
use yii\bootstrap\Nav as Nav2;
use yii\bootstrap\NavBar as NavBar2;
use yii\widgets\Breadcrumbs;
use hscstudio\heart\widgets\Nav;
use hscstudio\heart\widgets\NavBar;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'My Company',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
				'innerContainerOptions'=>[
                    'class' => 'container-fluid',
                ]
            ]);
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                /* $menuItems[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ]; */
				$menuItems[] =  
                    ['icon'=>'fa fa-user','label'=>'', 'url'=> '', 'items'=>[
						['icon'=>'fa fa-user','label' => 'Profile','url' => ['/student/student/profile'],],
                        '<li class="divider"></li>',
                        [
                            'icon'=>'fa fa-power-off',
                            'label' => 'Logout (' . Yii::$app->user->identity->person->name . ')',
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']
                        ]
                    ]                        
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container-fluid">
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container-fluid">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
