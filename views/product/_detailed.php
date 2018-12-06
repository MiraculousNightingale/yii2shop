<?php

use app\models\comment\Comment;
use app\models\product\Product;
use app\models\rating\Rating;
use app\models\user\User;
use kartik\rating\StarRating;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var Product $product */
/* @var Comment $comment */
/* @var User $user */
/* @var Rating $rating */
$user = Yii::$app->user->identity;

?>

<div class="product-detailed">
    <h1 class="text-center"><?= $product->title ?></h1>
    <h2 class="text-center"><?= $product->categoryName ?></h2>
    <img class="thumbnail" src="<?= $product->image ?>">
    <div class="center-block clearfix panel panel-body">
        <h3 class="text-right">Price: <?= $product->getEndPrice(Yii::$app->user->getId()) ?> UAH</h3>
        <?php if ($user) {
            if ($discount = $user->getDiscountOn($product->id)): ?>
                <h4 class="text-right">Discount: <?= $discount->percent ?>%</h4>
            <?php endif;
            if ($user->cart->hasItem($product->id)) {
                echo Html::button('In cart', ['class' => 'text-right btn btn-success disabled pull-right']);
            } else {
                echo Html::a('Add to cart', ['order/add-to-cart', 'productId' => $product->id], ['class' => 'text-right btn btn-info pull-right']);
            }
        } else {
            echo Html::a('Add to cart', ['site/login', 'productId' => $product->id], ['class' => 'text-right btn btn-info pull-right']);
        }
        ?>
    </div>
    <div>
        <?php $rateForm = ActiveForm::begin([
            'id' => 'rating-form',
            'action' => ['product/rate', 'id' => $product->id, 'userId' => $user->id],
            'options' => ['class' => 'form-group'],
        ]); ?>
        <?= $rateForm->field($rating, 'value')->widget(StarRating::className(), [
            'id' => 'rating-input',
            'pluginOptions' => ['step' => 0.5, 'onclick' => 'rate(event)'],
        ]) ?>
        <?= Html::submitButton('Rate', ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>
        <script>
            var rate = function (event) {
                var form = document.getElementById('rating-form');
                form.submit();
            }
        </script>
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

<div>

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
