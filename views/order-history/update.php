<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrderHistory */

$this->title = 'Update Order History: ' . ' ' . $model->record_id;
$this->params['breadcrumbs'][] = ['label' => 'Order Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->record_id, 'url' => ['view', 'id' => $model->record_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
