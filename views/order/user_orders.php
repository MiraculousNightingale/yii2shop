<?php

use app\models\user\User;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var User $model */

?>

<div class="order-list panel">
    <h3>Your order list:</h3>
    <?php foreach ($model->orders as $order):
        if (!$order->isCart()): ?>
            <div class="panel panel-info">
                <a class="panel-heading center-block btn-info clearfix"
                   href="<?= Url::toRoute(['order/detailed', 'id' => $order->id]) ?>">
                    <div class="pull-left">Order(<?= $order->id ?>)</div>
                    <div class="pull-right">Date: <?= $order->created_at ?></div>
                </a>
            </div>
        <?php endif;
    endforeach; ?>
</div>