<?php

// use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\widgets\DetailView;

use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\editable\Editable;


$this->title = Yii::t('order', 'Order') . ' #' . $model->invoice_no;
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="pull-right">
        <?= Html::a('Update', ['update', 'id' => $model->order_id], [
            'class' => 'btn btn-primary'
        ]) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->order_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('order', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        </div>
    </div>

    <div class="col-xs-12">
        <?= $this->render('_order_details', [
            'model' => $model,
            'subData' => $subData
        ]) ?>
    </div>

    <div class="col-xs-12">
    <?php \insolita\wgadminlte\Box::begin([
        'type' => \insolita\wgadminlte\Box::TYPE_INFO ,
        'solid' => true,
        'title' => Yii::t('order', 'Order Status History'),
        'collapse' => false,
        'footer' => Html::a(Yii::t('order', 'View full history'),
                    ['order/history', 'id' => $model->order_id],
                    ['title'=>'View full history'])
    ]);
    echo GridView::widget([
        'dataProvider' => $orderHistory,
        'columns' => [
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
                'format' => 'raw'
            ],
            'create_time:datetime',
        ],
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ]
    ]);
    \insolita\wgadminlte\Box::end(); ?>
    </div>

</div>