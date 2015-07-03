<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = Yii::t('order', 'Create Order');
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create" id="form-container">

    <?= $this->render('_form', [
        'model' => $model,
        'subData' => $subData
    ]) ?>

</div>
