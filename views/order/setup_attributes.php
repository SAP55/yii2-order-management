<?php

use yii\helpers\ArrayHelper;

use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\editable\Editable;

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
    	'staticValue' => ArrayHelper::getValue($subData['orderStatuses'], $model->order_status_id, '(not set)'),
    	'options' => [
            Yii::$app->settings->get('orderDefaults.OrderSatatus') => ['selected ' => $model->isNewRecord],
        ]
    ],
    'payment_method_id' => [
    	'type' => in_array('payment_method_id', $subData['editableAttributes']) ? Form::INPUT_DROPDOWN_LIST : Form::INPUT_STATIC,
    	'items' => $subData['paymentMethods'],
    	'staticValue' => ArrayHelper::getValue($subData['paymentMethods'], $model->payment_method_id, '(not set)'),
	],
    'payment_status_id' => [
    	'type' => in_array('payment_status_id', $subData['editableAttributes']) ? Form::INPUT_DROPDOWN_LIST : Form::INPUT_STATIC,
    	'items' => $subData['paymentStatusesList'],
    	'staticValue' => ArrayHelper::getValue($subData['paymentStatusesHtml'], $model->payment_status_id, '(not set)'),
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
    	'staticValue' => ArrayHelper::getValue($subData['shippingMethods'], $model->shipping_method_id, '(not set)'),
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
    'create_by' => [
    	'type' => Form::INPUT_STATIC,
	],
	'status' => [
    	'type' => Form::INPUT_STATIC,
	],
	'create_time' => [
    	'type' => Form::INPUT_STATIC,
	],
	'update_time' => [
    	'type' => Form::INPUT_STATIC,
	],
	'ip' => [
    	'type' => Form::INPUT_STATIC,
	],
	'user_agent' => [
    	'type' => Form::INPUT_STATIC,
	],
];
$columns = 1;

$attributes = array_intersect_key($attributes, array_flip($subData['visibleAttributes']), array_flip($model->getOrderTemplate()));

