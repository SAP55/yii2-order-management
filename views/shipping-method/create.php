<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ShippingMethod */

$this->title = 'Create Shipping Method';
$this->params['breadcrumbs'][] = ['label' => 'Shipping Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shipping-method-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
