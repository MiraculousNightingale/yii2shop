<?php

use app\models\product\Product;
use app\models\product\ProductFeatureForm;
use app\models\product\ProductForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $product ProductForm */
/* @var $features ProductFeatureForm */
$this->title = 'Update Product: ' . $product->title;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product->title, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'product' => $product,
        'features' => $features,
    ]) ?>

</div>
