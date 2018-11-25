<?php

use app\models\product\Product;
use app\models\user\User;
use yii\helpers\Html;

/* @var Product $model */
/* @var User $user */
$user = Yii::$app->user->identity;

?>

<div class="panel panel-primary product-item">
    <div class="product-picture" style="background: url(<?= $model->image ?>); background-size: cover;"></div>
    <?= Html::a($model->title, ['order/add-to-cart', 'productId' => $model->id], ['class' => 'panel-heading center-block']) ?>

    <article class="panel-body">
        <h3 class="">Price: <?= $model->price ?> UAH</h3>
        <?php if ($user->cart->hasItem($model->id)) {
            echo Html::button('In cart', ['class' => 'text-right btn btn-success disabled']);
        } else {
            echo Html::a('Add to cart', ['order/add-to-cart', 'productId' => $model->id], ['class' => 'text-right btn btn-warning']);
        }
        ?>
        <?= Html::a('Details', ['product/detalied-view', 'id' => $model->id], ['class' => 'text-right btn btn-info']) ?>
    </article>
</div>