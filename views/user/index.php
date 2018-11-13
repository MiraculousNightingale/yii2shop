<?php

use app\models\user\User;
use app\models\user\UserSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel UserSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'email:email',
            'username',
            [
                'attribute' => 'role',
                'value' => 'roleName',
                'filter' => User::getRoles(),
            ],
            [
                'attribute' => 'status',
                'value' => 'statusName',
                'filter' => User::getStatuses(),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
