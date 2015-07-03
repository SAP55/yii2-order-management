<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payment Statuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-status-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Payment Status', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'payment_status_id',
            'name',
            [
                'attribute'=>'color',
                'value'=>function ($model, $key, $index, $widget) {
                    return $model->getNameColor();
                },
                'format'=>'raw'
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
