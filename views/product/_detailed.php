<?php

use app\models\comment\Comment;
use app\models\product\Product;
use yii\helpers\Html;

/* @var Product $product */
/* @var Comment $comment */

?>

<div class="product-detailed">
    <h1 class="text-center"><?= $product->title ?></h1>
    <h2 class="text-center"><?= $product->categoryName ?></h2>
    <img class="thumbnail" src="<?= $product->image ?>">
    <div class="center-block clearfix panel panel-body">
        <h3 class="text-right">Price: <?= $product->price ?> UAH</h3>
        <?= Html::a('Add to cart', ['product/detalied-view', 'id' => $product->id], ['class' => 'btn btn-success pull-right ']) ?>
    </div>
    <article class="panel panel-body panel-info">
        <h3>Description</h3>
        <article><?= $product->description ?></article>
    </article>
    <ul class="list-group feature-list">
        <?php foreach ($product->features as $feature): ?>
            <li class="list-group-item feature-item">
                <h4 class="inline"><?= $feature->name ?></h4>
                <p> <?= $feature->value ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="comment-list panel panel-body panel-group">
    <h4 class="">Comments</h4>
    <?php foreach ($product->comments as $comm): ?>
        <div class="panel panel-info">
            <div class="panel-heading"><?= $comm->user->username ?></div>
            <article class="panel-body"><?= $comm->content ?></article>
            <section class="text-right panel-footer"><?= $comm->created_at ?></section>
        </div>
    <?php endforeach; ?>
</div>

<div class="comment-form panel panel-body">
    <h4 class="">Write a new comment:</h4>
    <?= $this->render('/comment/_user-form', ['model' => $comment, 'productId' => $product->id]) ?>
</div>