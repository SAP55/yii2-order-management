<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\OrderHistory */

$this->title = 'Create Order History';
$this->params['breadcrumbs'][] = ['label' => 'Order Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
