<?php

use app\models\category\Category;

use app\models\feature\Feature;
use dosamigos\multiselect\MultiSelect;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'feature_ids')->widget(MultiSelect::className(), [
        'options' => ['multiple' => true],
        'data' => ArrayHelper::map(Feature::find()->all(), 'id', 'name'),
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
