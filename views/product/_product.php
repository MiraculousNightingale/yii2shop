<?php

use app\models\product\Product;
use app\models\user\User;
use yii\helpers\Html;

/* @var Product $model */
/* @var User $user */
$user = Yii::$app->user->identity;

?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<div class="panel panel-primary product-item">
    <div class="product-picture" style="background: url('<?= $model->image ?>'); background-size: cover;"></div>
    <?= Html::a($model->title, ['product/detalied-view', 'id' => $model->id], ['class' => 'panel-heading center-block']) ?>
    <article class="panel-body">
        <h4 class="center-block" id="brand-name"><?= $model->getBrandName() ?></h4>
        <h3 class="center-block">Price: <?= $model->getEndPrice(Yii::$app->user->getId()) ?> UAH</h3>
        <?php if ($user) { ?>
            <div style="height: 19px">
            <?php if ($discount = $user->getDiscountOn($model->id)): ?>
                <h4 style="margin: 0">Discount: <?= $discount->percent ?>%</h4>
            <?php endif; ?>
            </div>
            <?php if ($user->cart->hasItem($model->id)) {
                echo Html::button('In cart', ['class' => 'text-right btn btn-success disabled']);
            } else {
                echo Html::a('Add to cart', ['order/add-to-cart', 'productId' => $model->id], ['class' => 'text-right btn btn-warning']);
            }
        } else {
            echo Html::a('Add to cart', ['site/login', 'productId' => $model->id], ['class' => 'text-right btn btn-warning']);
        }
        ?>
        <?= Html::a('Details', ['product/detalied-view', 'id' => $model->id], ['class' => 'text-right btn btn-info']) ?>
        <div class="center-block">
            Rating: <?= number_format($model->getTotalRating(), 1) ?>
        </div>
    </article>
</div>
