<?php

namespace sap55\order\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

use devgroup\TagDependencyHelper\ActiveRecordHelper;

use sap55\order\models\Order;
use sap55\order\models\query\OrderAttributeQuery;

/**
 * This is the model class for table "order_attribute".
 */
class OrderAttribute extends \yii\db\ActiveRecord
{

    const CACHE_ATTRUBUTES_LIST_DATA = 'orderAttributesListData';
    const CACHE_ORDER_STATUSES_HTML_DATA = 'orderStatusesHtmlData';

    const STATUS_SYSTEM = 101;
    const STATUS_USER = 100;

    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    private $statuses = [
        self::STATUS_DELETED => 'Deleted',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_SYSTEM => 'System',
        self::STATUS_USER => 'User',
    ];

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
    public static function getAttributesArray($status = null)
    {
        $cacheKey = 'OrderAttributeItems:' . implode(':', [
            $status
        ]);

        $items = Yii::$app->cache->get($cacheKey);
        if ($items !== false) {
            return $items;
        }

        $model = new Order;
        $data = self::find()
            ->select(['attribute_name', 'assignment'])
            ->orderBy('attribute_name ASC');
        if($status != null) {
            $data->where('assignment = :status', [':status' => self::STATUS_USER]);
        }

        $cache_tags = [
            ActiveRecordHelper::getCommonTag(static::className()),
        ];

        foreach ($data->asArray()->all() as $val) {
            $key = $val['attribute_name'];
            $items[$key] = $model->getAttributeLabel($key);

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
    public function rules()
    {
        return [
            [['assignment'], 'integer'],
            [['attribute_name'], 'string', 'max' => 64],
            [['attribute_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attribute_name' => 'Attribute Name',
            'assignment' => 'Assignment',
        ];
    }

    /**
     * @inheritdoc
     */
	public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (!$this->assignment) {
                    $this->assignment = self::STATUS_SYSTEM;
                }
            } else {

            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getAttributeLabelValue()
    {
        $labels = Order::attributeLabels();

        return $labels[$this->attribute_name];
    }

    /**
     * @return string
     */
    public function getStatus($status = null)
    {
        if ($status === null) {
            return Yii::t('order', $this->statuses[$this->status]);
        }
        return Yii::t('order', $this->statuses[$status]);
    }

    /**
     * @return array [[Statuses]]
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_attribute';
    }

    public static function find()
    {
        return new OrderAttributeQuery(get_called_class());
    }

}
