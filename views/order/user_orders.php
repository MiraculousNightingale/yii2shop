<?php

use app\models\user\User;
use yii\helpers\Html;

/* @var User $model */

?>

<div class="order-list panel">
    <h3>Your order list:</h3>
    <?php foreach ($model->orders as $order):
        if (!$order->isCart()): ?>
            <div class="panel panel-info">
                <div class="panel-heading clearfix">
                    <div class="pull-left">Order(<?= $order->id ?>)</div>
                    <div class="pull-right">Date: <?= $order->created_at ?></div>
                </div>
                <div>
                    <?= Html::a('Details', ['order/detailed', 'id' => $order->id], ['class' => 'btn btn-block btn-info']) ?>
                </div>
            </div>
        <?php endif;
    endforeach; ?>
</div>