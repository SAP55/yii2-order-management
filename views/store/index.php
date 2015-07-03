<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use kartik\grid\GridView;
// use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stores';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_menu') ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout'  => "{items}\n{pager}",
    'responsive' => true,
    'pjax' => true,
    'pjaxSettings' => [
        'neverTimeout' => true,
    ],
    'columns' => [
        ['class' => '\kartik\grid\CheckboxColumn'],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detailUrl' => Url::to(['store/detail']),
            'visible' => true,
            'enableRowClick' => true,
            'expandIcon' => '<i class="fa fa-plus-square-o"></i>',
            'collapseIcon' => '<i class="fa fa-minus-square-o"></i>',
        ],

        [
            'attribute' => 'name',
            'vAlign'=>'middle',
            // 'width'=>'220px',
        ],
        [
            'attribute' => 'url',
            'vAlign'=>'middle',
            'width'=>'220px',
            'format'=>'url',
        ],
        [
            'header' => Yii::t('store', 'Group'),
            'hAlign'=>'center', 
            'vAlign'=>'middle',
            'width'=>'220px',
            'attribute' => 'group_id',
            'value' => function ($model) {
                    return $model->group->name == null
                        ? '<span class="not-set">' . Yii::t('store', '(not set)') . '</span>'
                        : $model->group->name;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'=> $subData['storeGroups'], 
            'filterWidgetOptions'=>[
                'options' => [
                    // 'multiple' => true,
                ],
                'pluginOptions' => [
                    // 'data' => $orderStatuses,
                    'allowClear' => true
                ],
            ],
            'filterInputOptions' => ['placeholder' => 'Any groups'],
            'format'=>'raw',
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'contentOptions' => ['nowrap'=>'nowrap']
        ],
    ],
]); ?>