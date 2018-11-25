<?php

use app\models\order\Order;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\order\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'userName',
            'userEmail',
            'uniqueCount',
            'totalCount',
            'description',
            'statusName',
            'deliveryName',
            'destination',
//            'user_id',
            'created_at',
        ],
    ]) ?>

    <h3>Preview, with items</h3>

    <?= $this->render('_detailed', ['model' => $model]) ?>

    <?php if ($model->status != Order::STATUS_CART): ?>
        <div class="well">

        <span class="clearfix">
            <h4 class="pull-left">Send notifying email</h4>
            <?= Html::a('Send', ['order/send-notification-email', 'id' => $model->id], ['class' => 'btn btn-primary pull-right']) ?>
        </span>
            <span class="hint-block">Different statuses send different messages!</span>

            <!--TODO: Implement message sendings and order switch-->
            <?php if ($model->status == Order::STATUS_PENDING): ?>
                <span class="clearfix">
            <h4 class="pull-left">Set status to Completed</h4>
                    <?= Html::a('Complete', ['order/switch-status', 'id' => $model->id, 'status' => Order::STATUS_COMPLETED], ['class' => 'btn btn-success pull-right']) ?>
            </span>
            <?php endif; ?>

            <?php if ($model->status == Order::STATUS_COMPLETED): ?>
                <span class="clearfix">
            <h4 class="pull-left">Set status to Pending</h4>
                    <?= Html::a('Pending', ['order/switch-status', 'id' => $model->id, 'status' => Order::STATUS_PENDING], ['class' => 'btn btn-warning pull-right']) ?>
            </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>
