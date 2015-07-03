<?php

use yii\helpers\Html;
// use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Store */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'errorSummaryCssClass' => 'callout callout-danger',
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-sm-3',
            'offset' => 'col-sm-offset-2',
            'wrapper' => 'col-sm-7',
            'error' => '',
            'hint' => '',
        ],
    ],
]); ?>

<?= $form->errorSummary($model);?>

<div class="clearfix">

    <p class="pull-left">

    </p>

    <p class="pull-right">
      <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-danger']) ?>
      <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']) ?>
    </p>
</div>

<div class="row">
  <div class="col-xs-12 col-md-12 col-lg-12 pull-left">

    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'group_id')->dropDownList($subData['storeGroups']); ?>

    <?= $form->field($model, 'users_list')->widget(Select2::className(), [
        'data' => $subData['usersData'],
        'size' => Select2::MEDIUM,
        'options' => [
            'placeholder' => 'Select users',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>

    <?= $form->field($model, 'statuses_list')->widget(Select2::className(), [
        'data' => $subData['orderStatuses'],
        'size' => Select2::MEDIUM,
        'options' => [
            'placeholder' => 'Select users',
            'multiple'=>true,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>

    <?= $form->field($model, 'order_template')->widget(Select2::className(), [
        'data' => $subData['orderAttributes'],
        'size' => Select2::MEDIUM,
        'options' => [
            'placeholder' => 'Select users',
            'multiple' => true,
            'options' => [
                'store_id' => ['disabled' => true],
            ]
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>

    <?= $form->field($model, 'formula_purchase_p', [
        'inputOptions' => [
            'inline' => true,
            'class' => 'input-lg col-xs-5'
        ],
    ])->textInput()->hint('&nbsp;&nbsp;' .
      \yii\helpers\Html::a('<i class="fa fa-info"></i> Show help', '#',['onclick' => "$('#formula_hepl').toggle(); return false;"]),
      ['tag' => 'p']
    ); ?>

    <div class="well well-sm" id="formula_hepl" style="display: none;">
        <code>{{var_purchase_price}}</code> - variable from field Price RMB. <br>
        <code>-, +, *, /</code> - aviable arithmetics. <br>
        <code>n%</code> - use n/100. Example: for 20% use 0.2, for 120% use 1.2. <br>
        <p class="para">
       Используйте одну из этих констант для задания способа округления.
       </p><table class="doctable informaltable">
        
         <thead>
          <tr>
           <th>Константа</th>
           <th>Описание</th>
          </tr>

         </thead>

         <tbody class="tbody">
          <tr>
           <td><strong><code>PHP_ROUND_HALF_UP</code></strong></td>
           <td>
            Округляет <code class="parameter">val</code> в большую сторону от нуля  до <code class="parameter">precision</code> десятичных знаков, 
            если следующий знак находится посередине. Т.е. округляет 1.5 в 2 и -1.5 в -2.
           </td>
          </tr>

          <tr>
           <td><strong><code>PHP_ROUND_HALF_DOWN</code></strong></td>
           <td>
            Округляет <code class="parameter">val</code> в меньшую сторону к нулю до <code class="parameter">precision</code> десятичных знаков, 
            если следующий знак находится посередине. Т.е. округляет 1.5 в 1 и -1.5 в -1.
           </td>
          </tr>

          <tr>
           <td><strong><code>PHP_ROUND_HALF_EVEN</code></strong></td>
           <td>
            Округляет <code class="parameter">val</code> до <code class="parameter">precision</code> десятичных знаков 
            в сторону ближайшего четного знака.
           </td>
          </tr>

          <tr>
           <td><strong><code>PHP_ROUND_HALF_ODD</code></strong></td>
           <td>
            Округляет <code class="parameter">val</code> до <code class="parameter">precision</code> десятичных знаков 
            в сторону ближайшего нечетного знака.
           </td>
          </tr>

         </tbody>
        
       </table>
       <a href="http://php.net/manual/ru/function.round.php">http://php.net/manual/ru/function.round.php</a>
    </div>

    <?= $form->field($model, 'round_purchase_p_mode', [
        'inputOptions' => [
            'inline' => true,
            // 'class' => 'col-sm-3'
        ],
    ])->dropDownList($model->getRoundModesArray()); ?>

    <?= $form->field($model, 'round_purchase_p_precision')->textInput() ?>

<div class="clearfix">
  <p class="pull-left">
  </p>

  <p class="pull-right">
    <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-danger']) ?>
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-success']) ?>
  </p>
</div>

<?php ActiveForm::end(); ?>