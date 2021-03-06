<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\comment\Comment */

$this->title = 'Update Comment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => "Product $model->product_id", 'url' => ['product/view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="comment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
