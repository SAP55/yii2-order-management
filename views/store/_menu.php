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
            'label'   => Yii::t('user', 'Stores'),
            'url'     => ['/order/store/index'],
        ],
        [
            'label' => Yii::t('user', 'Groups'),
            'url'   => ['/order/store-group/index'],
        ],
        [
            'label' => Yii::t('user', 'Create'),
            'items' => [
                [
                    'label'   => Yii::t('user', 'New Store'),
                    'url'     => ['/order/store/create'],
                ],
                [
                    'label' => Yii::t('user', 'New Group'),
                    'url'   => ['/order/store-group/create'],
                ],
            ]
        ]
    ]
]) ?>