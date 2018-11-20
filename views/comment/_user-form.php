<?php

use app\models\comment\Comment;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var int $productId */
/* @var Comment $model */

?>

<div class="comment-form">

    <?php $form = ActiveForm::begin([
        'action' => ['comment/comment', 'productId' => $productId],
    ]); ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Comment', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>