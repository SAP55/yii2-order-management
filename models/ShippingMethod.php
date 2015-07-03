<?php

namespace sap55\order\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

use devgroup\TagDependencyHelper\ActiveRecordHelper;

use sap55\order\models\Order;

/**
 * This is the model class for table "shipping_method".
 *
 * @property integer $shipping_method_id
 * @property string $name
 *
 * @property Order[] $orders
 */
class ShippingMethod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => ActiveRecordHelper::className(),
                'cache' => 'cache',
            ],
        ];
    }

    /**
     * @return array [[DropDownList]]
     */
    public static function getShippingMethodArray()
    {
        $cacheKey = 'ShippingMethodItems:' . implode(':', []);

        $items = Yii::$app->cache->get($cacheKey);
        if ($items !== false) {
            return $items;
        }

        $data = self::find()->select(['shipping_method_id', 'name'])->orderBy('name ASC')->asArray()->all();

        $cache_tags = [
            ActiveRecordHelper::getCommonTag(static::className()),
        ];

        foreach ($data as $val) {
            $key = $val['shipping_method_id'];
            $items[$key] = $val['name'];

            $cache_tags[] = ActiveRecordHelper::getObjectTag(static::className(), $key);
        }

        Yii::$app->cache->set(
            $cacheKey,
            $items,
            86400,
            new TagDependency(
                [
                    'tags' => $cache_tags,
                ]
            )
        );
        return $items;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shipping_method';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shipping_method_id' => 'Shipping Method ID',
            'name' => 'Name',
        ];
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['shipping_method_id' => 'shipping_method_id']);
    }
}
