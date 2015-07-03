<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
$this->title .= Yii::t('order','Attributes Permission');
$this->params['breadcrumbs'][] = ['label' => Yii::t('order','Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('order','Settings'), 'url' => ['setting']];
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    [
        'attribute' => 'name',
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '180px',
        'value' => 'name',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $subData['roles'],
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'allowClear' => true
            ],
        ],
        'filterInputOptions' => [
            'placeholder' => Yii::t('order', 'Any roles'),
        ],
        'format' => 'raw'
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'visible_attributes',
        'hAlign' =>'center',
        'vAlign' =>'middle',
        'editableOptions' => function ($model, $key, $index) {
            return [
                'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
                'widgetClass' => 'kartik\widgets\Select2',
                'asPopover' => false,
                'type' => \kartik\popover\PopoverX::TYPE_DEFAULT,
                'placement' => \kartik\popover\PopoverX::ALIGN_TOP,
                'size' => 'md',
                'header' => Yii::t('order', 'Select visible attributes'),
                'displayValue' => Yii::t('order', 'Click for edit'),
                'showButtonLabels' => true,
                'formOptions' => [
                    'action' => Url::toRoute('setting/editable'),
                ],
                'options' => [
                    'data' => $model->getOrderAttributes(false),
                    'options' => [
                        'multiple' => true,
                    ]
                ],
            ];
        },
        'filter' => false,
        'format' =>'raw'
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'editable_attributes',
        'hAlign' =>'center', 
        'vAlign' =>'middle',
        'editableOptions' => function ($model, $key, $index) {
            return [
                'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
                'widgetClass' => 'kartik\widgets\Select2',
                'type' => \kartik\popover\PopoverX::TYPE_DEFAULT,
                'placement' => \kartik\popover\PopoverX::ALIGN_TOP,
                'size' => 'md',
                'header' => Yii::t('order', 'Select editable attributes'),
                'displayValue' => Yii::t('order', 'Click for edit'),
                'showButtonLabels' => true,
                'asPopover' => false,
                'formOptions' => [
                    'action' => Url::toRoute('setting/editable'),
                ],
                'options' => [
                    'data' => $model->getOrderAttributes(),
                    'options' => [
                        'multiple' => true,
                    ]
                ],
            ];
        },
        'filter' => false,
        'format' =>'raw'
    ],
];

?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'layout'  => "{items}\n{pager}",
                    'columns' => $columns,
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
            </div>
        </div>
    </div>
</div>