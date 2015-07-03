<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrderHistory */

$this->title = $model->record_id;
$this->params['breadcrumbs'][] = ['label' => 'Order Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-history-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->record_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->record_id], [
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
            'record_id',
            'order_id',
            'model',
            'attribute',
            'record_before:ntext',
            'record_after:ntext',
            'create_time',
        ],
    ]) ?>

</div>
