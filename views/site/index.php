<?php

/* @var $this yii\web\View */

use app\models\category\Category;
use app\models\product\ProductSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

$this->title = 'My Yii Application';

/* @var ProductSearch $searchModel */
?>
<div class="site-index">

    <div class="side-bar pull-left col-lg-2">
        <?php $form = ActiveForm::begin([
            'id' => 'filter-form',
            'action' => ['index'],
            'method' => 'get',
        ]) ?>

        <h4>Filters</h4>
        <h5>Category:</h5>
        <ul>
            <li><?= Html::a('All', '#', ['name' => '', 'onclick' => 'categoryFilter(this)']) ?></li>
            <?php foreach (Category::find()->all() as $category): ?>
                <li>
                    <?= Html::a($category->name, '#', ['name' => $category->name, 'onclick' => 'categoryFilter(this)']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="hidden"><?= $form->field($searchModel, 'categoryName')->textInput(['id' => 'category-input']) ?></div>
        <?= $form->field($searchModel, 'title')->textInput() ?>

        <?= $form->field($searchModel, 'fromPrice')->textInput() ?>

        <?= $form->field($searchModel, 'toPrice')->textInput() ?>


        <?= Html::submitButton('Search', ['class' => 'btn btn-success']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>

        <?php ActiveForm::end() ?>
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'div',
            'class' => 'list-wrapper product-list',
            'id' => 'list-wrapper',
        ],
        'layout' => "{pager}\n{items}\n{summary}",
        'itemView' => '/product/_product',
    ]) ?>

</div>

<script>
    var categoryFilter = function (eventCaller) {
        var form = document.getElementById('filter-form');
        var categoryInput = document.getElementById('category-input');
        categoryInput.value = eventCaller.getAttribute('name');
        form.submit();
    }
</script>
