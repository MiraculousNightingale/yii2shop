<?php

use app\models\product\Product;
use yii\helpers\Html;

/* @var Product $model */

?>

<div class="panel panel-primary product-item">
    <div class="product-picture" style="background: url(<?= $model->image ?>); background-size: cover;"></div>
    <div class="panel-heading"><?= $model->title ?></div>
    <article class="panel-body">
        <h3 class="">Price: <?= $model->price ?> UAH</h3>
        <?= Html::a('Add to cart', ['product/detalied-view', 'id' => $model->id], ['class' => 'text-right btn btn-info']) ?>
        <?= Html::a('Details', ['product/detalied-view', 'id' => $model->id], ['class' => 'text-right btn btn-info']) ?>
    </article>
</div>