<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
* @var yii\web\View $this
* @var app\modules\store\models\StoreGroup $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="store-group-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal', 'enableClientValidation' => false]); ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>
            
			<?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>

            <?= $form->field($model, 'formula_purchase_p')->textInput() ?>
        </p>
        <?php $this->endBlock(); ?>
        
        <?= \yii\bootstrap\Tabs::widget(
                 [
                   'encodeLabels' => false,
                    'items' => [ [
                    'label'   => 'StoreGroup',
                    'content' => $this->blocks['main'],
                    'active'  => true,
                ], ]
                 ]
    );
    ?>
        <hr/>

        <?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> '.($model->isNewRecord ? 'Create' : 'Save'), ['class' => $model->isNewRecord ?
        'btn btn-primary' : 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
