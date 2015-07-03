<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/**
* @var yii\web\View $this
* @var app\modules\order\models\OrderAttribute $model
*/

$this->title = 'Order Attribute View ' . $model->attribute_name . '';
$this->params['breadcrumbs'][] = ['label' => 'Order Attributes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->attribute_name, 'url' => ['view', 'attribute_name' => $model->attribute_name]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="order-attribute-view">

    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Edit', ['update', 'attribute_name' => $model->attribute_name],
        ['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> New Order Attribute', ['create'], ['class' => 'btn
        btn-success']) ?>
    </p>

        <p class='pull-right'>
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> List', ['index'], ['class'=>'btn btn-default']) ?>
    </p><div class='clearfix'></div> 

    
    <h3>
        <?= $model->attribute_name ?>    </h3>


    <?php $this->beginBlock('app\modules\order\models\OrderAttribute'); ?>

    <?php echo DetailView::widget([
    'model' => $model,
    'attributes' => [
    			'attribute_name',
			'assignment',
    ],
    ]); ?>

    <hr/>

    <?php echo Html::a('<span class="glyphicon glyphicon-trash"></span> Delete', ['delete', 'attribute_name' => $model->attribute_name],
    [
    'class' => 'btn btn-danger',
    'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'),
    'data-method' => 'post',
    ]); ?>

    <?php $this->endBlock(); ?>


    
    <?=
    \yii\bootstrap\Tabs::widget(
                 [
                     'id' => 'relation-tabs',
                     'encodeLabels' => false,
                     'items' => [ [
    'label'   => '<span class="glyphicon glyphicon-asterisk"></span> OrderAttribute',
    'content' => $this->blocks['app\modules\order\models\OrderAttribute'],
    'active'  => true,
], ]
                 ]
    );
    ?></div>
