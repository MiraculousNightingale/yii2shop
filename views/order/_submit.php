<?php

use app\models\order\Order;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @var bool $displayDestination */

?>

<div>
    <?php $form = ActiveForm::begin(['options' => ['id' => 'order-form']]); ?>

    <div class="hint-block">
        You may add additional description to the order, some comments or maybe wish someone a nice day!
    </div>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'delivery_type')->dropDownList(Order::getDeliveries(), ['onchange' => 'displayDestination(event)']) ?>

    <div class="hidden">
        <?= $form->field($model, 'loadDestination')->checkbox(['id' => 'loadDestination-checkbox', 'class' => 'hidden']) ?>
    </div>

    <?php if ($displayDestination): ?>

        <div id="destination-block">
            <div class="hint-block">
                Please fill in an understandable, coherent form.
            </div>
            <?= $form->field($model, 'destination')->textInput(['maxlength' => true]) ?>
        </div>

    <?php endif; ?>

    <?= Html::submitButton('Submit', ['class' => 'btn btn-success pull-right']) ?>

    <?php ActiveForm::end(); ?>

</div>

<script>
    var displayDestination = function (event) {
        var form = document.getElementById('order-form');
        var requestDestination = document.getElementById('loadDestination-checkbox');
        requestDestination.checked = true;
        form.submit();
    }
</script>