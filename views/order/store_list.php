<?php

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


?>

<div class="row">
  <div class="col-md-6 col-md-offset-3">
<?php
	\insolita\wgadminlte\Box::begin([
		'type' => \insolita\wgadminlte\Box::TYPE_PRIMARY,
		'solid' => true,
		'title' => Yii::t('order', 'Please select the store'),
		'collapse' => false
	]);
	echo '<div class="center-block">';
	foreach ($stores as $id => $name) {
		echo Html::a($name, ['create', 'store_id' => $id], ['class' => 'btn btn-default btn-block']);
	}
	echo '</div>';
	\insolita\wgadminlte\Box::end();
?>
    </div>
</div>