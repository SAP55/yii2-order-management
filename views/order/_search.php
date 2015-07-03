<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'invoice_no') ?>

    <?= $form->field($model, 'order_status_id') ?>

    <?= $form->field($model, 'store_id') ?>

    <?= $form->field($model, 'create_by') ?>

    <?php // echo $form->field($model, 'payment_method_id') ?>

    <?php // echo $form->field($model, 'payment_text') ?>

    <?php // echo $form->field($model, 'payment_status_id') ?>

    <?php // echo $form->field($model, 'shipping_method_id') ?>

    <?php // echo $form->field($model, 'shipping_text') ?>

    <?php // echo $form->field($model, 'purchase_price') ?>

    <?php // echo $form->field($model, 'delivery_price') ?>

    <?php // echo $form->field($model, 'total_price') ?>

    <?php // echo $form->field($model, 'tracking') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'forwarded_ip') ?>

    <?php // echo $form->field($model, 'user_agent') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
