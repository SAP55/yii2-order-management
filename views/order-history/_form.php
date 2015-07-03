<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrderHistory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-history-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'attribute')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'record_before')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'record_after')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
