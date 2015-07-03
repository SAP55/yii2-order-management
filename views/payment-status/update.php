<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PaymentStatus */

$this->title = 'Update Payment Status: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Payment Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->payment_status_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payment-status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'subData' => $subData
    ]) ?>

</div>
