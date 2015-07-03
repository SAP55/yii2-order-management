<?php

namespace sap55\order\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

use devgroup\TagDependencyHelper\ActiveRecordHelper;

/**
 * This is the model class for table "store_group".
 *
 * @property integer $group_id
 * @property string $name
 *
 * @property Store[] $stores
 */
class StoreGroup extends \yii\db\ActiveRecord
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
    public static function getStoreGroupsArray()
    {
        $cacheKey = 'StoreGroupsItems:' . implode(':', []) ;

        $items = Yii::$app->cache->get($cacheKey);
        if ($items !== false) {
            return $items;
        }

        $data = self::find()->select(['group_id', 'name'])->orderBy('name ASC')->asArray()->all();

        $cache_tags = [
            ActiveRecordHelper::getCommonTag(static::className()),
        ];

        foreach ($data as $val) {
            $key = $val['group_id'];
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
        return 'store_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 128],
            [['formula_purchase_p', 'round_purchase_p'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group ID',
            'name' => 'Name',
            'formula_purchase_p' => 'Formula for Purchase Price',
            'round_purchase_p' => 'Round for Purchase Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStores()
    {
        return $this->hasMany(\app\modules\store\models\Store::className(), ['store_group_id' => 'group_id']);
    }
}
