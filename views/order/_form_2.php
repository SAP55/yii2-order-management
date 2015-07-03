<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]); ?>



<div class="row order-form">
    <div class="col-xs-12">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Images</div>
                    <div class="panel-body">
                        <p>
                            При добавлении нового фото, кажатся что старая пропадает. Это не так!
                            Чтобы удалить загруженное фото из заказа, нужно нажать кнопку удалить и только!

                        </p>
                        <?= \nemmo\attachments\components\AttachmentsInput::widget([
                            'id' => 'file-input', // Optional
                            'model' => $model,
                            'pluginOptions' => [
                                'showUpload' => true, 
                                'previewFileType' => 'any',
                                'overwriteInitial' => false,
                                'maxFileCount' => 10 // Client max files
                            ],
                            'options' => [
                                'multiple' => true,
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-3 col-lg-3 pull-left">
                <div class="panel panel-default height">
                    <div class="panel-heading">Order Preferences</div>
                    <div class="panel-body">
                        <?php if (in_array('store_id', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'store_id')->dropDownList(
                            $subData['userStores'], [
                                'disabled' => !in_array('store_id', $subData['editableAttributes'])
                            ]
                        ); ?>
                        <?php } ?>

                        <?php if (in_array('invoice_no', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'invoice_no')->textInput([
                            'maxlength' => 128,
                            'readonly' => !in_array('invoice_no', $subData['editableAttributes'])
                        ]) ?>
                        <?php } ?>

                        <?php if (in_array('order_status_id', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'order_status_id')->dropDownList(
                            $subData['orderStatuses'], [
                                'options' => [
                                    Yii::$app->settings->get('orderDefaults.OrderSatatus') => ['selected ' => $model->isNewRecord],
                                    'disabled' => !in_array('order_status_id', $subData['editableAttributes'])
                                ]
                            ]
                        ); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-3 col-lg-3">
                <div class="panel panel-default height">
                    <div class="panel-heading">Payment Information</div>
                    <div class="panel-body">
                        <?php if (in_array('payment_method_id', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'payment_method_id')->dropDownList(
                            $subData['paymentMethods'], [
                                'options' => [
                                    Yii::$app->settings->get('orderDefaults.PaymentMethod') => ['selected ' => $model->isNewRecord],
                                    'disabled' => !in_array('payment_method_id', $subData['editableAttributes'])
                                ]
                            ]
                        ); ?>
                        <?php } ?>

                        <?php if (in_array('payment_text', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'payment_text')->textarea([
                            'rows' => 6,
                            'readonly' => !in_array('payment_text', $subData['editableAttributes'])
                        ]) ?>
                        <?php } ?>

                        <?php if (in_array('payment_status_id', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'payment_status_id')->dropDownList(
                            $subData['paymentStatusesList'],
                            [
                                'options' => [
                                    Yii::$app->settings->get('orderDefaults.ShippingMethod') => ['selected ' => $model->isNewRecord],
                                    'disabled' => !in_array('payment_status_id', $subData['editableAttributes'])
                                ]
                            ]
                        ); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-3 col-lg-3">
                <div class="panel panel-default height">
                    <div class="panel-heading">Shipping Address</div>
                    <div class="panel-body">
                        <?php if (in_array('shipping_method_id', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'shipping_method_id')->dropDownList(
                            $subData['shippingMethods'],
                            [
                                'options' => [
                                    Yii::$app->settings->get('orderDefaults.OrderSatatus') => ['selected ' => $model->isNewRecord],
                                    'disabled' => !in_array('shipping_method_id', $subData['editableAttributes'])
                                ]
                            ]
                        ); ?>
                        <?php } ?>

                        <?php if (in_array('shipping_text', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'shipping_text')->textarea([
                            'rows' => 6,
                            'readonly' => !in_array('shipping_text', $subData['editableAttributes'])
                        ]) ?>
                        <?php } ?>

                        <?php if (in_array('tracking', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'tracking')->textInput([
                            'maxlength' => 256,
                            'readonly' => !in_array('tracking', $subData['editableAttributes'])
                        ]) ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-3 col-lg-3 pull-right">
                <div class="panel panel-default height">
                    <div class="panel-heading">Additional info</div>
                    <div class="panel-body">
                        <?php if (in_array('purchase_price', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'purchase_price')->textInput([
                            'maxlength' => 15,
                            'readonly' => !in_array('purchase_price', $subData['editableAttributes'])
                        ]) ?>
                        <?php } ?>

                        <?php if (in_array('sale_price', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'sale_price')->textInput([
                            'maxlength' => 15,
                            'readonly' => !in_array('sale_price', $subData['editableAttributes'])
                        ]) ?>
                        <?php } ?>

                        <?php if (in_array('shipping_price', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'shipping_price')->textInput([
                            'maxlength' => 15,
                            'readonly' => !in_array('shipping_price', $subData['editableAttributes'])
                        ]) ?>
                        <?php } ?>

                        <?php if (in_array('total_price', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'total_price')->textInput([
                            'maxlength' => 15,
                            'readonly' => !in_array('total_price', $subData['editableAttributes'])
                        ]) ?>
                        <?php } ?>

                        <?php if (in_array('comment', $subData['visibleAttributes'])) { ?>
                        <?= $form->field($model, 'comment')->textarea([
                            'rows' => 3,
                            'readonly' => !in_array('comment', $subData['editableAttributes'])
                        ]) ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success pull-right']) ?>
                    </div>
                </div>
            </div>
        </div>        

    </div>
</div>

<?php ActiveForm::end(); ?>


<div class="row">
    <div class="col-xs-12 col-md-4 col-lg-4 pull-left">
        <div class="panel panel-default height">
            <div class="panel-heading">Order Preferences</div>
            <div class="panel-body">
                <?php if (in_array('order_status_id', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong',
                        $model->getAttributeLabel('order_status_id') . ': '
                    );
                    echo Editable::widget([
                        'model' => $model,
                        'attribute' => 'order_status_id',
                        'displayValueConfig' => $subData['orderHtmlStatuses'],
                        'data' => $subData['orderStatuses'],
                        'inputType' => Editable::INPUT_DROPDOWN_LIST ,
                        'options' => ['class'=>'form-control',],
                        'editableValueOptions'=>['class'=>'text-danger'],
                        'size' => 'md',
                        'formOptions' => [
                            'action' => Url::toRoute('order/editable'),
                        ],
                        'afterInput' => function ($form, $widget) {
                            echo $form->field($widget->model, 'order_id')->hiddenInput()->label(false);
                        }
                    ]);
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('store_id', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('store_id') . ': '
                    );
                    echo $model->store->name;
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('create_by', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('create_by') . ': '
                    );
                    echo $model->createBy->username;
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('status', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('status') . ': '
                    );
                    echo $model->getStatus($model->status);
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('create_time', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('create_time') . ': '
                    );
                    echo Yii::$app->formatter->asDatetime($model->create_time);
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('update_time', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('update_time') . ': '
                    );
                    echo Yii::$app->formatter->asDatetime($model->update_time);
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('ip', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('ip') . ': '
                    );
                    echo $model->ip;
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('user_agent', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('user_agent') . ': '
                    );
                    echo $model->user_agent;
                    echo Html::tag('br');
                } ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4 col-lg-4">
        <div class="panel panel-default height">
            <div class="panel-heading">Payment Information</div>
            <div class="panel-body">
                <?php if (in_array('payment_status_id', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('payment_status_id') . ': '
                    );
                    echo Editable::widget([
                        'model' => $model, 
                        'attribute' => 'payment_status_id',
                        'displayValueConfig' => $subData['paymentStatusesHtml'],
                        'data' => $subData['paymentStatusesList'],
                        'inputType'=>Editable::INPUT_DROPDOWN_LIST ,
                        'options' => ['class'=>'form-control',],
                        'editableValueOptions'=>['class'=>'text-danger'],
                        'size' => 'md',
                        'formOptions' => [
                            'action' => Url::toRoute('order/editable'),
                        ],
                        'afterInput' => function ($form, $widget) {
                            echo $form->field($widget->model, 'order_id')->hiddenInput()->label(false);
                        }
                    ]);
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('payment_method_id', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('payment_method_id') . ': '
                    );
                    echo $model->paymentMethod->name;
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('payment_text', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('payment_text') . ': '
                    );
                    echo Yii::$app->formatter->asNtext($model->payment_text);
                    echo Html::tag('br');
                } ?>
            </div>
        </div>

        <div class="panel panel-default height">
            <div class="panel-heading">Shipping Address</div>
            <div class="panel-body">
                <?php if (in_array('shipping_method_id', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('shipping_method_id') . ': '
                    );
                    echo $model->shippingMethod->name;
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('shipping_text', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('shipping_text') . ': '
                    );
                    echo Yii::$app->formatter->asNtext($model->shipping_text);
                    echo Html::tag('br');
                } ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4 col-lg-4 pull-right">
        
        <div class="panel panel-default height">
            <div class="panel-heading">Prices</div>
            <div class="panel-body">
                <?php if (in_array('shipping_price', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('shipping_price') . ': '
                    );
                    echo Yii::$app->formatter->asCurrency($model->shipping_price);
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('purchase_price', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('purchase_price') . ': '
                    );
                    echo Yii::$app->formatter->asCurrency($model->purchase_price);
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('sale_price', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('sale_price') . ': '
                    );
                    echo Yii::$app->formatter->asCurrency($model->sale_price);
                    echo Html::tag('br');
                } ?>
                <?php if (in_array('total_price', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('total_price') . ': '
                    );
                    echo Yii::$app->formatter->asCurrency($model->total_price);
                    echo Html::tag('br');
                } ?>
            </div>
        </div>

        <div class="panel panel-default height">
            <div class="panel-heading">Comment</div>
            <div class="panel-body">
                <?php if (in_array('comment', $subData['visibleAttributes'])) {
                    echo Html::tag(
                        'strong', 
                        $model->getAttributeLabel('comment') . ': '
                    );
                    echo Yii::$app->formatter->asNtext($model->comment);
                    echo Html::tag('br');
                } ?>
            </div>
        </div>

    </div>
</div>