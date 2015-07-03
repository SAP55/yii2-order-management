<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Store */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="clearfix">

    <p class="pull-left">

    </p>

    <p class="pull-right">
        <?= Html::a('Update', ['update', 'id' => $model->store_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->store_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>

<div class="row">
    <div class="col-md-6 col-md-offset-3">

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'url:url',
        [
            'label' => Yii::t('store', 'Group Name'),
            'attribute' => 'group_id',
            'value' => $model->group->name == null
                        ? '<span class="not-set">' . Yii::t('store', '(not set)') . '</span>'
                        : $model->group->name,
            'format' => 'html',
        ],
        [
            'label' => Yii::t('store', 'Formula for Sale Price'),
            'value' => $model->formula_purchase_p,
            'format' => 'html',
        ],
        [
            'label' => Yii::t('store', 'Formula Round Mode'),
            'value' => $model->round_purchase_p_mode,
            'format' => 'html',
        ],
        [
            'label' => Yii::t('store', 'Formula Precision'),
            'value' => $model->round_purchase_p_precision,
            'format' => 'html',
        ],
        [
            'label' => Yii::t('store', 'Assigned Users'),
            'attribute' => 'users',
            'value' => implode(', ', ArrayHelper::getColumn($model->users, 'username')),
            'format' => 'raw',
        ],
        [
            'label' => Yii::t('store', 'Order Statuses'),
            'attribute' => 'orderStatuses',
            'value' => implode(', ', ArrayHelper::getColumn($model->orderStatuses, 'name')),
            'format' => 'raw',
        ],
        [
            'label' => Yii::t('store', 'Order Template'),
            'attribute' => 'orderTemplate',
            'value' => implode('<br />', ArrayHelper::getColumn($model->orderTemplate, 'attribute_name')),
            'format' => 'html',
        ],
    ],
]) ?>

    </div>
</div>
