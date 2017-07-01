<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

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
<?php $this->beginBody();
// 简单权限控制
$role = 0;
if (!empty(Yii::$app->user->identity->username) && in_array(Yii::$app->user->identity->username, [
        13161057904,
    ])
) {
    $role = 1;
}
?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '众筹夺宝',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $menuItems[] = ['label' => '用户地址', 'items' => [
        ['label' => '收货地址', 'url' => ['/user-address']],
        ['label' => '全国所有城市', 'url' => ['/city']],
    ]];

    $menuItems[] = ['label' => '评论管理', 'items' => [
        ['label' => '产品评论', 'url' => ['/comments']],
    ]];

    $menuItems[] = ['label' => '订单管理', 'items' => [
        ['label' => '订单', 'url' => ['/order']],
    ]];

    $menuItems[] = ['label' => '图片管理', 'items' => [
        ['label' => '产品介绍图片', 'url' => ['/image']],
    ]];

    $menuItems[] = ['label' => '视频管理', 'items' => [
        ['label' => '产品介绍视频', 'url' => ['/video']],
    ]];
    $menuItems[] = ['label' => '产品管理', 'items' => [
        ['label' => '产品', 'url' => ['/product']],
    ]];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        if ($role == 1) {
            $menuItems[] = ['label' => '会员管理', 'items' => [
                ['label' => '会员列表', 'url' => ['/user']],
            ]];
        }
        $menuItems[] = ['label' => '管理员(' . Yii::$app->user->identity->username . ')', 'items' => [
            ['label' => '退出', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
        ]];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <!--<p class="pull-right"><?= Yii::powered() ?></p>-->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
