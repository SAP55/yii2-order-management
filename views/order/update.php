<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = Yii::t('order', 'Update Order') . ' #' . $model->invoice_no;
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->invoice_no, 'url' => ['view', 'id' => $model->order_id]];
$this->params['breadcrumbs'][] = Yii::t('order', 'Update');
?>
<div class="order-update">

    <?= $this->render('_form', [
        'model' => $model,
        'subData' => $subData
    ]) ?>

</div>
