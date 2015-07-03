<?php

use yii\helpers\Url;
use yii\widgets\Menu;

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= Yii::t('order', 'Order Module settings') ?>
        </h3>
    </div>
    <div class="panel-body">
        <?= Menu::widget([
            'options' => [
                'class' => 'nav nav-pills nav-stacked'
            ],
            'items' => [
                ['label' => Yii::t('order', 'Asset permissions'),  'url' => ['/order/setting/permissions']],
                ['label' => Yii::t('order', 'Asset default values'),  'url' => ['/order/setting/default']],
            ]
        ]) ?>
    </div>
</div>