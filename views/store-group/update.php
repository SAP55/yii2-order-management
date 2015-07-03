<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\store\models\StoreGroup $model
 */

$this->title = 'Store Group Update ' . $model->name . '';
$this->params['breadcrumbs'][] = ['label' => 'Store Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'group_id' => $model->group_id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="store-group-update">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> View', ['view', 'group_id' => $model->group_id], ['class' => 'btn btn-info']) ?>
    </p>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
