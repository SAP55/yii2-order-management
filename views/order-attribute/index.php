<?php

use yii\helpers\Url;
use yii\helpers\Html;

use kartik\grid\GridView;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var app\modules\order\models\search\OrderAttributeSearch $searchModel
*/

$this->title = 'Order Attributes';
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    [
        'attribute' => 'attribute_name',
        'label' => Yii::t('order', 'Attribute Label'),
        'hAlign'=>'center',
        // 'width'=>'280px',
        'value' => function ($model) {
            return $model->getAttributeLabelValue();
        },
        'filter' => false,
        'format' => 'raw'
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute'=>'assignment',
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'editableOptions'=> function ($model, $key, $index, $this) {
            return [
                'header' => Yii::t('order', 'Assignment Status'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST ,
                'displayValueConfig' => $model->getStatuses(),
                'data' => $model->getStatuses(),
                'formOptions' => [
                    'action' => Url::toRoute('order-attribute/editable'),
                ]
            ];
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'data' => $subData['statuses'],
            'options' => [
                'placeholder' => Yii::t('order', 'Any status'),
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ],
        'format' => 'raw'
    ],
];
?>

<?= $this->render('_menu') ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns,
    'layout'  => "{items}\n{pager}",
    'showPageSummary' => false,
    'striped' => true,
    'condensed' => true,
    'responsive' => true,
    'hover' => true,
    'pjax' => true,
    'pjaxSettings' => [
        'neverTimeout' => true,
    ]

]); ?>
