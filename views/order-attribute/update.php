<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\order\models\OrderAttribute $model
 */

$this->title = 'Order Attribute Update ' . $model->attribute_name . '';
$this->params['breadcrumbs'][] = ['label' => 'Order Attributes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->attribute_name, 'url' => ['view', 'attribute_name' => $model->attribute_name]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="order-attribute-update">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> View', ['view', 'attribute_name' => $model->attribute_name], ['class' => 'btn btn-info']) ?>
    </p>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
