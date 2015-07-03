<?php

namespace sap55\order\controllers;

use Yii;
use yii\rbac\Item;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

use sap55\order\models\search\AuthItemSearch;
use sap55\order\models\AuthItem;
use sap55\order\models\OrderAttribute;
use app\models\User;

use sap55\order\models\OrderStatus;
use sap55\order\models\PaymentStatus;
use sap55\order\models\PaymentMethod;
use sap55\order\models\ShippingMethod;


class SettingController extends \yii\web\Controller
{
    /** @inheritdoc */
    public $defaultAction = 'permissions';

    public $subData = [];

    protected $type = Item::TYPE_ROLE;

    public function actionPermissions()
    {
    	$subData['orderAttributesAll'] = $this->module->getOrderAttributes('all');
        $subData['orderAttributesUser'] = $this->module->getOrderAttributes('user');

        $searchModel = new AuthItem();

        $subData['roles'] = AuthItem::getRolesArray();

        return $this->render('permissions', [
        	'dataProvider' => $searchModel->search(\Yii::$app->request->get()),
            'searchModel' => $searchModel,
        	'subData' => $subData,
    	]);
    }

    public function actionDefault()
    {
        $settings = Yii::$app->settings;

        $defaultValues = [
            'OrderSatatus' => [
                'key' => 'OrderSatatus',
                'value' => $settings->get('orderDefaults.OrderSatatus'),
                'availableValues' => OrderStatus::getOrderStatusArray(),
            ],
            'PaymentStatus' => [
                'key' => 'PaymentStatus',
                'value' => $settings->get('orderDefaults.PaymentStatus'),
                'availableValues' => PaymentStatus::getPaymentStatusArray()
            ],
            'PaymentMethod' => [
                'key' => 'PaymentMethod',
                'value' => $settings->get('orderDefaults.PaymentMethod'),
                'availableValues' => PaymentMethod::getPaymentMethodArray()
            ],
            'ShippingMethod' => [
                'key' => 'ShippingMethod',
                'value' => $settings->get('orderDefaults.ShippingMethod'),
                'availableValues' => ShippingMethod::getShippingMethodArray()
            ],
        ];

        return $this->render('default', [
            'defaultValues' => $defaultValues
        ]);
    }

    public function actionEditable() 
    {

        if (Yii::$app->request->post('hasEditable') && Yii::$app->request->post('AuthItem')) {

            $posted = Yii::$app->request->post('AuthItem');

            if ($id = Yii::$app->request->post('editableKey')) {

            } else {
                if ($posted['attribute_name']) {
                    $id = $posted['attribute_name'];
                }
            }

            $model = AuthItem::findOne($id);

            if (!ArrayHelper::isAssociative($posted, true)) {
                $post['AuthItem'] = current($posted);
            } else {
                $post['AuthItem'] = $posted;
            }

            if ($model->load($post) && $model->save()) {
                echo \yii\helpers\Json::encode(['output' => Yii::t('order', "Click for edit"), 'message' => '']);
            } else {
                echo \yii\helpers\Json::encode(['output' => Yii::t('order', "Error"), 'message' => '']);
            }

            return;
        }
    }

    public function actionUpdate() 
    {
        Yii::info('$Yii::$app->request->post = ' . print_r(Yii::$app->request->post(), true), 'pushNotifications');

        if (Yii::$app->request->post('hasEditable')) {

            $posted = Yii::$app->request->post();

            if ($key = Yii::$app->request->post('editableKey')) {
                Yii::$app->settings->set($key, $posted[$key], 'orderDefaults', 'integer');

                echo \yii\helpers\Json::encode(['output' => $posted[$key], 'message' => '']);
            } else {
                echo \yii\helpers\Json::encode(['output' => Yii::t('order', "Error"), 'message' => '']);
            }

            return;
        }
    }

}