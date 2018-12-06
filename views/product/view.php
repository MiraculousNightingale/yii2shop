<?php

use app\models\product\Product;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model Product */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="form-group">
        <h4 class="text-center">Product thumbnail</h4>
        <img class="img-thumbnail center-block col-lg-6" id="preview" src="<?= $model->image ?>">
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'brandName',
            'description',
            'price',
            'amount',
            'categoryName',
            'image',
        ],
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $model->getDetailedFeatures(),
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'userName',
            'content:ntext',
            'created_at',

            ['class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::toRoute(['comment/view', 'id' => $model->id]);
                    }

                    if ($action === 'update') {
                        return Url::toRoute(['comment/update', 'id' => $model->id]);
                    }
                    if ($action === 'delete') {
                        return Url::toRoute(['comment/delete', 'id' => $model->id]);
                    }
                },
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>

    <!--    TODO: DONT FORMAT THIS PAGE, IT CONTAINS COMMENTED CODE IN PHPDOC, KOSTYL PIZDEC BTW.   -->
    <?php /**
     * <?= DetailView::widget([
     * 'model' => $model,
     * 'attributes' => array_merge(
     * [
     * 'id',
     * 'title',
     * 'brandName',
     * 'description',
     * 'price',
     * 'amount',
     * 'categoryName',
     * ],
     * $model->getDetailedFeatures(),
     * [
     * 'created_at',
     * 'updated_at',
     * ]
     * ),
     * ]) ?>
     */ ?>

</div>
