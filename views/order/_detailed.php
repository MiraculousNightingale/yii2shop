<?php
/**
 * Created by PhpStorm.
 * User: wenceslaus
 * Date: 11/24/18
 * Time: 11:11 PM
 */

use app\models\order\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var Order $model */

?>

<div class="panel panel-body panel-primary">
    <h3 class="panel-heading">Order ID <?= $model->id ?></h3>
    <h4>Made at: <?= $model->created_at ?></h4>
    <div class="h3">Ordered products:</div>
    <div class="">
        <?php foreach ($model->items as $item): ?>
            <div class="panel panel-info">
                <a class="panel-heading btn-info center-block clearfix"
                   href="<?= Url::toRoute(['product/detalied-view', 'id' => $item->product->id]) ?>">
                    <span class="pull-left"><?= $item->product->title ?></span>
                    <span class="pull-right"><?= $item->product->getBrandName() ?></span>
                </a>
                <div class="panel-body">
                    <span>Price: <?= $item->product->price ?> UAH</span>
                    <h5>Amount: <?= $item->amount ?></h5>
                    <h5>Total: <?= $item->price ?></h5>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <h4>Total: <?= $model->price ?> UAH</h4>
    <span class="pull-right alert <?= ($model->status == Order::STATUS_COMPLETED) ? 'alert-success' : 'alert-warning' ?>">Status: <?= $model->getStatusName() ?></span>
</div>