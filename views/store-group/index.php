<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var app\modules\store\models\search\StoreGroupSearch $searchModel
*/

$this->title = 'Store Groups';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/store/_menu') ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout'  => "{items}\n{pager}",
    'columns' => [
        ['class' => '\kartik\grid\CheckboxColumn'],
        'group_id',
        'name',
        [
            'class' => 'yii\grid\ActionColumn',
            'urlCreator' => function($action, $model, $key, $index) {
                $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                return \yii\helpers\Url::toRoute($params);
            },
            'contentOptions' => ['nowrap'=>'nowrap']
        ],
    ],
]); ?>
