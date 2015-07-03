<?php

use yii\helpers\Url;
use yii\helpers\Html;

use kartik\editable\Editable;

$this->title .= Yii::t('order', 'Default settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('order','Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('order','Settings'), 'url' => ['setting']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">

            <div class="form-horizontal">
				<div class="row">
					<div class="col-xs-12 col-md-12 col-lg-12 pull-left">

		            <?php foreach ($defaultValues as $key => $value) {
		            	echo '<div class="form-group field-store-name required">';
						echo '<label class="control-label col-sm-3" for="store-name">' . $key . '</label>';

						echo '<div class="col-sm-7">';
						echo Editable::widget([
						    'name' => $key,
						    'value' => $value['value'],
						    'header' => $value['value'],
						    'format' => Editable::FORMAT_LINK,
						    'inputType' => Editable::INPUT_DROPDOWN_LIST,
						    'data' => $value['availableValues'],
						    'displayValueConfig' => $value['availableValues'],
						    'showButtonLabels' => true,
		                	'asPopover' => false,
						    'options' => [
						    	'class' => 'form-control',
						    	'prompt' => Yii::t('order','Select default value'),
					    	],
						    'editableValueOptions' => [
						    	'class' => 'text-danger'
					    	],
						    'formOptions' => [
						    	'action' => Url::to(['setting/update'])
						    ],
						    'afterInput' => Html::hiddenInput('editableKey', $key)
						]);
						echo '</div>';
						echo '</div>';
					} ?>
					</div>
				</div>
			</div>

            </div>
        </div>
    </div>
</div>