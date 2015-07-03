<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrderStatus */

$this->title = 'Update Order Status: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Order Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->order_status_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'subData' => $subData
    ]) ?>

</div>
