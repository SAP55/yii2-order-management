<?php

namespace sap55\order;

use Yii;
use yii\base\Module as BaseModule;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

use sap55\order\models\OrderAttribute;
use sap55\order\models\AuthItem;
use sap55\order\models\Store;


class Module extends BaseModule
{

    CONST CACHE_ORDER = 'orderModule';

    public $usernameField = 'username';

    private $bgColors = [
        'bg-light-blue',
        'bg-green',
        'bg-yellow',
        'bg-red',
        'bg-aqua',
        'bg-purple',
        'bg-blue',
        'bg-navy',
        'bg-teal',
        'bg-maroon',
        'bg-black',
        'bg-gray',
        'bg-olive',
        'bg-lime',
        'bg-orange',
        'bg-fuchsia',
    ];

    /**
     * @var string The prefix for order module URL.
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'order';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        '<id:\d+>'      => 'order/view',
        'setting'       => 'setting',
        'dashboard'     => 'order/index',
        '<action:\w+>'  => 'order/<action>',
    ];


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }


    public function getVisiableAttributes()
    {
        $attributes = $this->getAttributesByRole()->visible_attributes;

        return $attributes;
    }

    public function getEditableAttributes()
    {
        $attributes = ArrayHelper::merge($this->getAttributesByRole()->editable_attributes, $this->getOrderAttributes('system'));

        return $attributes;
    }

    public function getRolesByUser($user_id = 0)
    {
        if ($user_id == 0) {
            $user_id = Yii::$app->user->id;
        }

        $user_roles = Yii::$app->authManager->getRolesByUser($user_id);
        foreach ($user_roles as $role) {
            $user_role[] = $role->name;
        }

        return $user_role[0];
    }

    public function getOrderAttributes($assignment = 'all')
    {
        if ($assignment == 'all') {
            $order_attributes = OrderAttribute::find()->asArray()->all();
        } elseif ($assignment == 'user') {
            $order_attributes = OrderAttribute::find()->user()->asArray()->all();
        } elseif ($assignment == 'system') {
            $order_attributes = OrderAttribute::find()->system()->asArray()->all();
        }

        if ($order_attributes !== null)
            return ArrayHelper::getColumn($order_attributes, 'attribute_name');
        else
            return [];
    }

    public function getAttributesByRole()
    {
        $model = AuthItem::find()
            ->select('name')
            ->where(['name' => $this->getRolesByUser()])
            ->with('visibleAttributes', 'editableAttributes')
            ->one();

        if ($model->visibleAttributes !== null || $model->editableAttributes !== null)
            return $model;
        else
            throw new NotFoundHttpException('The project not have any roles configured.');
    }

    public function getStoresIdsByUser($user_id = 0)
    {
        return ArrayHelper::getColumn($this->getStoreQueryByUserId($user_id), 'store_id');
    }

    public function getStoresByUser($user_id = 0)
    {
        return ArrayHelper::map($this->getStoreQueryByUserId($user_id), 'store_id', 'name');
    }

    public function getOrderStatusesByUser($user_id = 0)
    {
        $orderStatuses = [];

        foreach ($this->getStoreQueryByUserId($user_id) as $store) {
            $orderStatus = ArrayHelper::map($store->orderStatuses, 'order_status_id', 'name');
            $orderStatuses = ArrayHelper::merge($orderStatuses, $orderStatus);
        }

        return array_unique($orderStatuses);
    }

    public function getUsersArray()
    {
        $model = \Yii::createObject([
            'class'    => Yii::$app->user->identityClass,
        ]);

        $query = $model::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return ArrayHelper::map($model::find()->select(['id', $this->usernameField])->orderBy($this->usernameField . ' ASC')->asArray()->all(), 'id', $this->usernameField);
    }

    public function getBgColors()
    {
        foreach ($this->bgColors as $value) {
            $colors[$value] = $value;
            $options[$value] = ['class' => $value];
            $colorsHtml[$value] = '<span class="' . $value . '">' . $value . '</span>';
        }

        return [
            'colors' => $colors,
            'options' => $options,
            'colorsHtml' => $colorsHtml
        ];
    }


    private function getStoreQueryByUserId($user_id)
    {
        if ($user_id == 0) {
            $user_id = Yii::$app->user->id;
        }

        return Store::find()->joinWith('users')->where(['user.id' => $user_id])->with([
            'orderStatuses'
        ])->all();
    }

}
