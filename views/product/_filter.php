<?php

use app\models\category\Category;
use yii\helpers\Html;

?>

<div class="side-bar">
    <h4>Filters</h4>
    <h5>Category:</h5>
    <ul>
        <?php foreach (Category::find()->all() as $category): ?>
            <li>
                <?= Html::a($category->name, ['site/filter']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <h5>Price:</h5>
</div>