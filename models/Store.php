<?php

namespace sap55\order\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

use devgroup\TagDependencyHelper\ActiveRecordHelper;

use sap55\order\models\query\StoreQuery;

use Rezzza\Formulate\Formula;

/**
 * This is the model class for table "store".
 *
 * @property integer $store_id
 * @property integer $group_id
 * @property integer $round_purchase_p_precision
 * @property string $name
 * @property string $url
 * @property string $round_purchase_p_mode
 * @property string $formula_purchase_p
 *
 * @property Order[] $orders
 */
class Store extends \yii\db\ActiveRecord
{
    const ROUND_HALF_UP = 'PHP_ROUND_HALF_UP';
    const ROUND_HALF_DOWN = 'PHP_ROUND_HALF_DOWN';
    const ROUND_HALF_EVEN = 'PHP_ROUND_HALF_EVEN';
    const ROUND_HALF_ODD = 'PHP_ROUND_HALF_ODD';

    private $round_modes = [
        self::ROUND_HALF_UP => 'ROUND_HALF_UP',
        self::ROUND_HALF_DOWN => 'ROUND_HALF_DOWN',
        self::ROUND_HALF_EVEN => 'ROUND_HALF_EVEN',
        self::ROUND_HALF_ODD => 'ROUND_HALF_ODD',
    ];

    public function validateFormula($attribute, $params)
    {
        if (!empty($this->$attribute)) {
            $formula = new Formula($this->$attribute, Formula::CALCULABLE);
            $formula->setParameter('var_purchase_price', 100);
            // $formula->setIsCalculable(true);
            if(!$formula->render()) {
                $this->addError($attribute, 'Incorrect formula.');
            }
        }
    }

    /**
     * @return array [[DropDownList]]
     */
    public static function getStoreArray()
    {
        $cacheKey = 'StoreItems:' . implode(':', []) ;

        $items = Yii::$app->cache->get($cacheKey);
        if ($items !== false) {
            return $items;
        }

        $userModel = \Yii::createObject([
            'class'    => Yii::$app->user->identityClass,
        ]);

        $stores_id = $userModel::find()->where(['id' => Yii::$app->user->id])->with('stores')->one();

        $data = self::find()->where([
            'store_id' => ArrayHelper::getColumn($stores_id->stores, 'store_id')
        ])->orderBy('name ASC')->asArray()->all();

        $cache_tags = [
            ActiveRecordHelper::getCommonTag(static::className()),
        ];

        foreach ($data as $val) {
            $key = $val['store_id'];
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
        return 'store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['url', 'round_purchase_p_mode'], 'string', 'max' => 255],
            ['url', 'url', 'defaultScheme' => 'http'],
            [['group_id', 'round_purchase_p_precision'], 'integer'],
            [['round_purchase_p_mode', 'formula_purchase_p'], 'string'],
            // ['formula_purchase_p', 'validateFormula', 'skipOnEmpty' => true, 'skipOnError' => false],

            [['users_list', 'statuses_list', 'order_template'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \voskobovich\behaviors\ManyToManyBehavior::className(),
                'relations' => [
                    'users_list' => 'users',
                    'statuses_list' => 'orderStatuses',
                    'order_template' => 'orderTemplate',
                ],
            ],
            [
                'class' => ActiveRecordHelper::className(),
                'cache' => 'cache',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_id' => 'Store ID',
            'group_id' => 'Group Name',
            'name' => 'Store Name',
            'url' => 'Url',
        ];
    }

    /**
     * @return string
     */
    public function getRoundMode($mode = null)
    {
        if ($mode === null) {
            return Yii::t('store', $this->round_modes[$this->round_purchase_p_mode]);
        }
        return Yii::t('store', $this->round_modes[$mode]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getRoundModesArray()
    {
        return $this->round_modes;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(StoreGroup::className(), ['group_id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['store_id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers() {
        return $this->hasMany(Yii::$app->user->identityClass, ['id' => 'user_id'])
            ->viaTable('user_to_store', ['store_id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatuses() {
        return $this->hasMany(OrderStatus::className(), ['order_status_id' => 'order_status_id'])
            ->viaTable('status_to_store', ['store_id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderTemplate() {
        return $this->hasMany(OrderAttribute::className(), ['attribute_name' => 'attribute_name'])
            ->viaTable('order_attribute_to_store', ['store_id' => 'store_id'])->asArray();
    }

    public static function find()
    {
        return new StoreQuery(get_called_class());
    }

}
