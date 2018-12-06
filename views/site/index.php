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
        <h4>Sort</h4>
        <?= $form->field($searchModel, 'sortBrand')->hiddenInput(['id' => 'sort-brand-input']) ?>
        <ul>
            <li>
                <?= Html::a('Ascending', '#', ['name' => 'brand', 'onclick' => 'sort(this,1)']) ?>
            </li>
            <li>
                <?= Html::a('Descending', '#', ['name' => 'brand', 'onclick' => 'sort(this,2)']) ?>
            </li>
        </ul>
        <?= $form->field($searchModel, 'sortTitle')->hiddenInput(['id' => 'sort-title-input']) ?>
        <ul>
            <li>
                <?= Html::a('Ascending', '#', ['name' => 'title', 'onclick' => 'sort(this,1)']) ?>
            </li>
            <li>
                <?= Html::a('Descending', '#', ['name' => 'title', 'onclick' => 'sort(this,2)']) ?>
            </li>
        </ul>
        <?= $form->field($searchModel, 'sortPrice')->hiddenInput(['id' => 'sort-price-input']) ?>
        <ul>
            <li>
                <?= Html::a('Ascending', '#', ['name' => 'price', 'onclick' => 'sort(this,1)']) ?>
            </li>
            <li>
                <?= Html::a('Descending', '#', ['name' => 'price', 'onclick' => 'sort(this,2)']) ?>
            </li>
        </ul>
        <?= $form->field($searchModel, 'sortRating')->hiddenInput(['id' => 'sort-rating-input']) ?>
        <ul>
            <li>
                <?= Html::a('Ascending', '#', ['name' => 'rating', 'onclick' => 'sort(this,1)']) ?>
            </li>
            <li>
                <?= Html::a('Descending', '#', ['name' => 'rating', 'onclick' => 'sort(this,2)']) ?>
            </li>
        </ul>

        <h4>Filters</h4>

        <?= $form->field($searchModel, 'categoryName')->hiddenInput(['id' => 'category-input']) ?>

        <ul>
            <li><?= Html::a('All', '#', ['name' => '', 'onclick' => 'categoryFilter(this)']) ?></li>
            <?php foreach (Category::find()->all() as $category): ?>
                <li>
                    <?= Html::a($category->name, '#', ['name' => $category->name, 'onclick' => 'categoryFilter(this)']) ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?= $form->field($searchModel, 'brandName')->textInput() ?>

        <?= $form->field($searchModel, 'title')->textInput() ?>

        <?= $form->field($searchModel, 'fromPrice')->textInput() ?>
        <?= $form->field($searchModel, 'toPrice')->textInput() ?>

        <!--  Searching by rating values is redundant.  -->
        <div class="hidden">
            <?= $form->field($searchModel, 'fromRating')->textInput() ?>
            <?= $form->field($searchModel, 'toRating')->textInput() ?>
        </div>

        <!--    Planned as feature filter, but implementation is way too difficult    -->
        <?php if (/*$searchModel->categoryName*/
            0 == 1): ?>

            <h4>Feature filters</h4>

            <?php foreach (Category::findOne(['name' => $searchModel->categoryName])->features as $feature): ?>
                <h3><?= $feature->name ?></h3>
            <?php endforeach; ?>

        <?php endif; ?>

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
        'layout' => "{items}\n{pager}\n{summary}",
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
    var sort = function (eventCaller, order) {
        var form = document.getElementById('filter-form');
        var attr = eventCaller.getAttribute('name');
        document.getElementById('sort-brand-input').value = 0;
        document.getElementById('sort-title-input').value = 0;
        document.getElementById('sort-price-input').value = 0;
        document.getElementById('sort-rating-input').value = 0;
        var sortInput = document.getElementById('sort-' + attr + '-input');
        sortInput.value = order;
        form.submit();
    }
</script>
