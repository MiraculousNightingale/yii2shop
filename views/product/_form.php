<?php

use app\models\brand\Brand;
use app\models\category\Category;
use app\models\product\ProductFeatureForm;
use app\models\product\ProductForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $product ProductForm */
/* @var $features ProductFeatureForm */
/* @var $form ActiveForm */

?>
<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'product-form']]); ?>

    <img class="center-block col-lg-6" id="preview"
         src="<?= (isset($product->source->image)) ? $product->source->image : '' ?>">

    <?= $form->field($product, 'imageFile')->fileInput(['onchange' => 'loadFile(event)']) ?>

    <?= $form->field($product, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($product, 'brand_id')->dropDownList(ArrayHelper::map(Brand::find()->all(), 'id', 'name')) ?>

    <?= $form->field($product, 'description')->textarea() ?>

    <?= $form->field($product, 'price')->textInput() ?>

    <?= $form->field($product, 'amount')->textInput() ?>

    <?= $form->field($product, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'name'), ['onchange' => 'loadFeatures(event)']) ?>

    <!--  Hidden checkbox used with javaScript  -->
    <div class="hidden">
        <?= $form->field($product, 'loadCategory')->checkbox(['id' => 'loadCategory-checkbox', 'class' => 'hidden']) ?>
    </div>

    <?php //Dynamically render features by chosen category.
    foreach ($features->attributes as $name => $value) {
        echo $form->field($features, $name)->textInput();
    } ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    var loadFile = function (event) {
        var output = document.getElementById('preview');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
    var loadFeatures = function (event) {
        var form = document.getElementById('product-form');
        var requestCategory = document.getElementById('loadCategory-checkbox');
        requestCategory.checked = true;
        form.submit();
    }
</script>
