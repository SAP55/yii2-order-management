<?php

// use Yii;
use yii\helpers\Html;

use kartik\grid\GridView;
use yii\widgets\DetailView;

$this->title = Yii::t('order', 'Order History for') . ' #' . $model->invoice_no;
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

	<div class="col-xs-12">
        <div class="panel panel-default height">
            <div class="panel-heading"><?= Yii::t('order', 'Order Status History'); ?></div>
            <div class="panel-body">
                <?php
                echo GridView::widget([
                    'dataProvider' => $orderHistory,
                    'columns' => [
                        [
                            'attribute' => 'attribute',
                            'value' => function ($orderHistory) {
                                return $orderHistory->order->getAttributeLabel($orderHistory->attribute);
                            },
                        ],
                        [
                            'attribute' => 'record_before',
                            'value' => function ($orderHistory) {
                                return $orderHistory->getRalationValue($orderHistory->record_before);
                            },
                        ],
                        [
                            'attribute' => 'record_after',
                            'value' => function ($orderHistory) {
                                return $orderHistory->getRalationValue($orderHistory->record_after);
                            },
                        ],
                        [
                            'attribute' => 'create_by',
                            'value' => function ($orderHistory, $key, $index, $widget) {
                                return Html::a($orderHistory->createBy->username,
                                    '#',
                                    ['title'=>'View author detail', 'onclick'=>'alert("This will open the author page.\n\nDisabled for this demo!")']);
                            },
                            'format'=>'raw'
                        ],
                        'create_time:datetime',

                    ],
                    'striped' => true,
                    'condensed' => true,
                    'responsive'=>true,
                    'hover'=>true,
                    'pjax'=>true,
                    'pjaxSettings'=>[
                        'neverTimeout'=>true,
                        // 'beforeGrid'=>'My fancy content before.',
                        // 'afterGrid' => '<p class="text-center">Center aligned text.</p>',
                    ]
                ]);

                ?>
            </div>
        </div>
    </div>
