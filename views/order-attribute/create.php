<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var app\modules\order\models\OrderAttribute $model
*/

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Order Attributes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-attribute-create">

    <p class="pull-left">
        <?= Html::a('Cancel', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
    </p>
    <div class="clearfix"></div>

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
