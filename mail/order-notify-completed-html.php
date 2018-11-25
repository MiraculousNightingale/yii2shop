<?php

use app\models\order\Order;
use app\models\user\User;
use yii\helpers\Html;

/** @var $user User */
/** @var $order Order */

$link = Yii::$app->urlManager->createAbsoluteUrl(['order/detailed', 'id' => $order->id]);
?>
<div class="password-reset" style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
    <h4 style="font-size: 18px">Hello <?= Html::encode($user->username) ?>!</h4>

    <h5>Your order(<?= $order->id ?>) made at <?= $order->created_at ?> has been completed.</h5>

    <p>
        It means that you can received your products from your local 'Peace Date Yii Shop' department, or if you chose
        service delivery, the courier is going to be at your place soon!
    </p>
    <p>Please contact us for further information if needed. Use the following link to see your order:</p>
    <p><?= Html::a($link, $link) ?></p>
</div>