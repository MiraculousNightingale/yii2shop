<?php

use app\models\order\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var Order $cart */
?>

<div class="panel panel-body">
    <div class="h3">Ordered products:</div>
    <div class="">

        <?php $form = ActiveForm::begin([
            'id' => 'ordered-form',
            'action' => ['order/cart'],
        ]);
        foreach ($cart->items as $item): ?>
            <div class="panel panel-info">
                <a class="panel-heading center-block btn-info"
                   href="<?= Url::toRoute(['product/detalied-view', 'id' => $item->id]) ?>"><?= $item->product->title ?></a>
                <div class="panel-body">
                    <span>Price: <?= $item->discountApplies() ? $item->product->getEndPrice($cart->user_id) : $item->product->price ?>
                        UAH</span>
                    <p><?= ($discount = $item->discountApplies()) ? 'Discount: ' . $discount->percent . '%' : '' ?></p>
                    <?= $form->field($item, "[$item->id]amount")->textInput() ?>
                    <?= Html::a('Remove', ['order/remove-from-cart', 'productId' => $item->product_id], ['class' => 'btn btn-danger pull-right']) ?>
                </div>
            </div>
        <?php endforeach;
        echo Html::submitButton('Apply product amounts', ['class' => ['btn btn-success', 'pull-right', (count($cart->items) < 1) ? 'disabled' : '']]);
        ActiveForm::end(); ?>

        <?php if (count($cart->items) > 0): ?>
            <h4>Total: <?= $cart->price ?> UAH</h4>
        <?php else: ?>
            <h4>Currently you have no products in your cart.</h4>
        <?php endif; ?>

    </div>
</div>

<?php if (count($cart->items) > 0): ?>
    <div class="well panel-body">
        <h3 class="pull-left">Submit your order:</h3>
        <?= Html::a('Submit order', ['order/submit-order'], ['class' => 'btn btn-primary pull-right btn-']) ?>
    </div>
<?php endif; ?>

