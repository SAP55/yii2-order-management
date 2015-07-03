<?php

namespace sap55\order\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

use devgroup\TagDependencyHelper\ActiveRecordHelper;

use kartik\helpers\Html;

use sap55\order\models\Order;
use sap55\order\models\Store;

/**
 * This is the model class for table "order_status".
 *
 * @property integer $order_status_id
 * @property string $name
 * @property string $color
 *
 * @property Order[] $orders
 */
class OrderStatus extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => ActiveRecordHelper::className(),
                'cache' => 'cache', // optional option - application id of cache component
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'color'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_status_id' => 'Order Status ID',
            'name' => 'Name',
            'color' => 'Color'
        ];
    }

    public function getNameColor()
    {
        return Html::badge($this->name, [
            'class' => 'btn btn-xs btn-block ' . $this->color,
        ]);
    }

    /**
     * @return array [[DropDownList]]
     */
    public static function getOrderStatusArray($asHtml = false)
    {
        $cacheKey = 'OrderStatusItems:' . implode(':', [
            intval($asHtml)
        ]) ;

        $items = Yii::$app->cache->get($cacheKey);
        if ($items !== false) {
            return $items;
        }

        $data = self::find()->select(['order_status_id', 'name', 'color'])->orderBy('name ASC')->asArray()->all();

        $cache_tags = [
            ActiveRecordHelper::getCommonTag(static::className()),
        ];

        foreach ($data as $val) {
            $key = $val['order_status_id'];
            $items[$key] = $asHtml ? Html::badge($val['name'], [
                'class' => 'btn btn-xs btn-block ' . $val['color'],
            ]) : $val['name'];

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
     * @return array [[DropDownList]]
     */
    public static function getOrderStatusHtmlArray()
    {
        return self::getOrderStatusArray(true);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['order_status_id' => 'order_status_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        return parent::afterSave($insert, $changedAttributes);
    }
}
