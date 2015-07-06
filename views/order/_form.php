<?php

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

$attributes = [
    'invoice_no' => [
    	'type' => in_array('invoice_no', $subData['editableAttributes']) ? Form::INPUT_TEXT : Form::INPUT_STATIC,
    	'options' => [
    		'maxlength' => 128,
    	]
	],
    'store_id' => [
    	'type' => Form::INPUT_HIDDEN_STATIC,
    	// 'type' => in_array('store_id', $subData['editableAttributes']) ? Form::INPUT_DROPDOWN_LIST : Form::INPUT_STATIC,
    	'items' => $subData['userStores'],
    	'staticValue' => ArrayHelper::getValue($subData['userStores'], $model->store_id, '(not set)'),
  //   	'options' => [
  //   		'onchange' => '$.post( "'.Yii::$app->urlManager->createUrl('order/form?store_id=').'" + $(this).val(), function(data) {
  //       		$("#form-container").html( data );
  //       	})',
		// ]
    ],
    'order_status_id' => [
    	'type' => in_array('order_status_id', $subData['editableAttributes']) ? Form::INPUT_DROPDOWN_LIST : Form::INPUT_STATIC,
    	'items' => $subData['orderStatuses'],
    	'staticValue' => ArrayHelper::getValue($subData['orderStatuses'], $model->order_status_id, Yii::t('order', '(not set)')),
    	'options' => [
            Yii::$app->settings->get('orderDefaults.OrderSatatus') => ['selected ' => $model->isNewRecord],
        ]
    ],
    'payment_method_id' => [
    	'type' => in_array('payment_method_id', $subData['editableAttributes']) ? Form::INPUT_DROPDOWN_LIST : Form::INPUT_STATIC,
    	'items' => $subData['paymentMethods'],
    	'staticValue' => ArrayHelper::getValue($subData['paymentMethods'], $model->payment_method_id, Yii::t('order', '(not set)')),
	],
    'payment_status_id' => [
    	'type' => in_array('payment_status_id', $subData['editableAttributes']) ? Form::INPUT_DROPDOWN_LIST : Form::INPUT_STATIC,
    	'items' => $subData['paymentStatusesList'],
    	'staticValue' => ArrayHelper::getValue($subData['paymentStatusesHtml'], $model->payment_status_id, Yii::t('order', '(not set)')),
    ],
    'payment_text' => [
    	'type' => in_array('payment_text', $subData['editableAttributes']) ? Form::INPUT_TEXTAREA : Form::INPUT_STATIC,
    	'options' => [
    		'rows' => 6,
    	]
    ],
    'shipping_method_id' => [
    	'type' => in_array('shipping_method_id', $subData['editableAttributes']) ? Form::INPUT_DROPDOWN_LIST : Form::INPUT_STATIC,
    	'items' => $subData['shippingMethods'],
    	'staticValue' => ArrayHelper::getValue($subData['shippingMethods'], $model->shipping_method_id, Yii::t('order', '(not set)')),
	],
    'shipping_tracking' => [
    	'type' => in_array('shipping_tracking', $subData['editableAttributes']) ? Form::INPUT_TEXT : Form::INPUT_STATIC,
    ],
    'shipping_text' => [
    	'type' => in_array('shipping_text', $subData['editableAttributes']) ? Form::INPUT_TEXTAREA : Form::INPUT_STATIC,
    	'options' => [
    		'rows' => 6,
    	]
    ],
    'purchase_price' => [
    	'type' => in_array('purchase_price', $subData['editableAttributes']) ? Form::INPUT_TEXT : Form::INPUT_STATIC,
    	'fieldConfig' => [
    		'addon' => [
	        	'append' => [
		            'content' => '<i class="fa fa-usd"></i>'
		        ]
	        ]
        ]
	],
    'sale_price' => [
    	'type' => in_array('sale_price', $subData['editableAttributes']) ? Form::INPUT_TEXT : Form::INPUT_STATIC,
    	'fieldConfig' => [
    		'addon' => [
	        	'append' => [
		            'content' => '<i class="fa fa-usd"></i>'
		        ]
	        ]
        ]
	],
	'shipping_price' => [
    	'type' => in_array('shipping_price', $subData['editableAttributes']) ? Form::INPUT_TEXT : Form::INPUT_STATIC,
    	'fieldConfig' => [
    		'addon' => [
	        	'append' => [
		            'content' => '<i class="fa fa-usd"></i>'
		        ]
	        ]
        ]
	],
	'comment' => [
    	'type' => in_array('comment', $subData['editableAttributes']) ? Form::INPUT_TEXTAREA : Form::INPUT_STATIC,
    	'options' => [
    		'rows' => 6,
    	]
    ],
];
$columns = 1;

$attributes = array_intersect_key($attributes, array_flip($subData['visibleAttributes']), array_flip($model->getOrderTemplate()));

$col = 0;
if (
	$commonAttributes = array_intersect_key($attributes, array_flip([
		'invoice_no', 'store_id', 'order_status_id'
	]))
) { $col++; }

if (
	$paymentAttributes = array_intersect_key($attributes, array_flip([
		'payment_method_id', 'payment_status_id', 'payment_text'
	]))
) { $col++; }

if (
	$shippingAttributes = array_intersect_key($attributes, array_flip([
	'shipping_method_id', 'shipping_tracking', 'shipping_text'
]))
) { $col++; }

if (
	$priceAttributes = array_intersect_key($attributes, array_flip([
	'shipping_price', 'purchase_price', 'sale_price'
]))
) { $col++; }
$commentsAttributes = array_intersect_key($attributes, array_flip([
	'comment'
]));

if ($col == 0) $col = 1;
$col_md = 12/$col;

$form = ActiveForm::begin([
	'type' => ActiveForm::TYPE_VERTICAL,
	'errorSummaryCssClass' => 'callout callout-danger',
]);

?>

<?=$form->errorSummary($model);?>

<div class="clearfix">

    <p class="pull-left">

    </p>

    <p class="pull-right">
    	<?= Html::a(Yii::t('order', 'Cancel'), ['index'], ['class' => 'btn btn-danger']) ?>
    	<?= Html::submitButton($model->isNewRecord ? Yii::t('order', 'Create') : Yii::t('order', 'Update'), ['class' => 'btn btn-success']) ?>
    </p>
</div>

<div class="row">
	<div class="col-xs-12 col-md-<?=$col_md; ?> col-lg-<?=$col_md; ?> pull-left">
	<?php if ($commonAttributes) {
	\insolita\wgadminlte\Box::begin([
	     'type' => \insolita\wgadminlte\Box::TYPE_PRIMARY,
	     'solid' => true,
	     'title' => Yii::t('order', 'Order Details'),
	     'collapse' => false
	]);
	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => $columns,
	    'attributes' => $commonAttributes
	]);
	\insolita\wgadminlte\Box::end();
	} ?>
	</div>


	<div class="col-xs-12 col-md-<?=$col_md; ?> col-lg-<?=$col_md; ?> pull-left">
	<?php if ($paymentAttributes) {
	\insolita\wgadminlte\Box::begin([
		'type' => \insolita\wgadminlte\Box::TYPE_PRIMARY,
		'solid' => true,
		'title' => Yii::t('order', 'Payment Information'),
		'collapse' => false
	]);
	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => $columns,
	    'attributes' => $paymentAttributes
	]);
	\insolita\wgadminlte\Box::end();
	} ?>
	</div>


	<div class="col-xs-12 col-md-<?=$col_md; ?> col-lg-<?=$col_md; ?> pull-left">
	<?php if ($shippingAttributes) {
	\insolita\wgadminlte\Box::begin([
		'type' => \insolita\wgadminlte\Box::TYPE_PRIMARY,
		'solid' => true,
		'title' => Yii::t('order', 'Shipping Address'),
		'collapse' => false
	]);
	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => $columns,
	    'attributes' => $shippingAttributes
	]);
	\insolita\wgadminlte\Box::end();
	} ?>
	</div>


	<div class="col-xs-12 col-md-<?=$col_md; ?> col-lg-<?=$col_md; ?> pull-left">
	<?php if ($priceAttributes) {
	\insolita\wgadminlte\Box::begin([
		'type' => \insolita\wgadminlte\Box::TYPE_PRIMARY,
		'solid' => true,
		'title' => Yii::t('order', 'Price'),
		'collapse' => false
	]);
	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => $columns,
	    'attributes' => $priceAttributes
	]);
	\insolita\wgadminlte\Box::end();
	} ?>
	</div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8 col-lg-8">
    <?php \insolita\wgadminlte\Box::begin([
	     'type' => \insolita\wgadminlte\Box::TYPE_DEFAULT,
	     'solid' => false,
	     'title' => Yii::t('order', 'Attachments Files'),
	     'collapse' => false
	 ]); ?>
	<p>
        При добавлении нового фото, кажатся что старая пропадает. Это не так!
        Чтобы удалить загруженное фото из заказа, нужно нажать кнопку удалить и только!
    </p>
    <?= \nemmo\attachments\components\AttachmentsInput::widget([
        'id' => 'file-input',
        'model' => $model,
        'pluginOptions' => [
            'showUpload' => true,
            'previewFileType' => 'any',
            'overwriteInitial' => true,
            'maxFileCount' => 10,
            'pluginEvents' => [
				'filebatchselected' => 'function(event, files) { $input.fileinput("upload"); }',
			],
        ],

        'options' => [
            'multiple' => true,
        ]
    ]);
	\insolita\wgadminlte\Box::end(); ?>
    </div>

    <div class="col-xs-12 col-md-4 col-lg-4">
	<?php if ($commentsAttributes) {
	\insolita\wgadminlte\Box::begin([
		'type' => \insolita\wgadminlte\Box::TYPE_PRIMARY,
		'solid' => true,
		'title' => Yii::t('order', 'Comments'),
		'collapse' => false
	]);
	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => $columns,
	    'attributes' => $commentsAttributes
	]);
	\insolita\wgadminlte\Box::end();
	} ?>
    </div>
</div>

<div class="clearfix">

    <p class="pull-left">

    </p>

    <p class="pull-right">
    	<?= Html::a(Yii::t('order', 'Cancel'), ['view', 'id' => $model->order_id], ['class' => 'btn btn-danger']) ?>
    	<?= Html::submitButton($model->isNewRecord ? Yii::t('order', 'Create') : Yii::t('order', 'Update'), ['class' => 'btn btn-success']) ?>
    </p>
</div>

<?php
ActiveForm::end();