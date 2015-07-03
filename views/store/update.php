<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Store */

$this->title = 'Update Store: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->store_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="store-update">

    <?= $this->render('_form', [
        'model' => $model,
        'subData' => $subData,
    ]) ?>

</div>
