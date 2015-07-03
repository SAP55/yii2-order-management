<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\ColorInput

/* @var $this yii\web\View */
/* @var $model app\models\PaymentStatus */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-status-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'color')->dropDownList($subData['bgColors']['colors'],
        [
        	'options' => $subData['bgColors']['options'],
        	'encode' => false
        ]
    ); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
