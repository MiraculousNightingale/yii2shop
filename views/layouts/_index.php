<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\models\category\Category;
use app\models\User;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            !Yii::$app->user->isGuest ? ['label' => 'Your cart', 'url' => ['/order/cart']] : '',
            !Yii::$app->user->isGuest ? ['label' => 'Your orders', 'url' => ['/order/user-orders', 'userId' => Yii::$app->user->getId()]] : '',
            !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin() ?
                [
                    'label' => 'Administrate',
                    'items' => [
                        Yii::$app->user->identity->isOverlord() ?
                            ['label' => 'Manage Users', 'url' => ['/user/index'], 'options' => ['class' => 'btn-info']] : '',
                        ['label' => 'Manage Discounts', 'url' => ['/discount/index']],
                        ['label' => 'Manage Orders', 'url' => ['/order/index']],
                        ['label' => 'Manage Products', 'url' => ['/product/index']],
                        ['label' => 'Manage Brands', 'url' => ['/brand/index']],
                        ['label' => 'Manage Features', 'url' => ['/feature/index']],
                        ['label' => 'Manage Categories', 'url' => ['/category/index']],
                    ],
                ] : '',
            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container col-lg-12">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>

        <?= $content ?>

    </div>
</div>

<footer class="footer col-lg-12">
    <div class="container">
        <p class="pull-left"><?= Yii::$app->params['company'] ?></p>

        <p class="pull-right"><?= Yii::$app->params['powered'] ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
