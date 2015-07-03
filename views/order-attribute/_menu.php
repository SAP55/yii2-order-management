<?php

use yii\helpers\Url;
use yii\bootstrap\Nav;

?>

<?= Nav::widget([
    'options' => [
        'class' => 'nav-tabs',
        'style' => 'margin-bottom: 15px'
    ],
    'items' => [
        [
            'label'   => Yii::t('order', 'Order Attributes'),
            'url'     => ['/order/order-attribute/index'],
        ],
        [
            'label' => Yii::t('order', 'Settings'),
            'items' => [
                [
                    'label'   => Yii::t('order', 'Update Attributes'),
                    'url'     => ['/order/order-attribute/install'],
                ],
            ]
        ]
    ]
]) ?>