<?php

namespace sap55\order\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

use devgroup\TagDependencyHelper\ActiveRecordHelper;

use kartik\helpers\Html;

use sap55\order\models\Order;

/**
 * This is the model class for table "payment_status".
 *
 * @property integer $payment_status_id
 * @property string $name
 * @property string $color
 *
 * @property Order[] $orders
 */
class PaymentStatus extends \yii\db\ActiveRecord
{

    /**
     * @return array [[DropDownList]]
     */
    public static function getPaymentStatusArray($asHtml = false)
    {
        $cacheKey = 'PaymentStatusItems:' . implode(':', [
            intval($asHtml)
        ]) ;

        $items = Yii::$app->cache->get($cacheKey);
        if ($items !== false) {
            return $items;
        }

        $data = self::find()->select(['payment_status_id', 'name', 'color'])->orderBy('name ASC')->asArray()->all();

        $cache_tags = [
            ActiveRecordHelper::getCommonTag(static::className()),
        ];

        foreach ($data as $val) {
            $key = $val['payment_status_id'];
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
        return 'payment_status';
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
            'payment_status_id' => 'Payment Status ID',
            'name' => 'Name',
            'color' => 'Color',
        ];
    }

    public function getName()
    {
        return Html::badge($this->name, [
            'class' => 'btn btn-xs btn-block ' . $this->color,
        ]);
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
    public static function getPaymentStatusHtmlArray()
    {
        return self::getPaymentStatusArray(true);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['payment_status_id' => 'payment_status_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        return parent::afterSave($insert, $changedAttributes);
    }
}
