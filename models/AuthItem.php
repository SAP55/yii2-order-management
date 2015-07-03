<?php

namespace sap55\order\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

use devgroup\TagDependencyHelper\ActiveRecordHelper;

use sap55\order\models\query\AuthItemQuery;
use sap55\order\models\OrderAttribute;

/**
 * This is the model class for table \Yii::$app->authManager->itemTable.
 *
 * @property string $name
 */
class AuthItem extends \yii\db\ActiveRecord
{

    const CACHE_ROLES_LIST_DATA = 'roles';

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['visible_attributes', 'editable_attributes', 'name'], 'safe']
        ];
    }

    /** @inheritdoc */
	public function behaviors()
    {
        return [
            [
                'class' => \voskobovich\behaviors\ManyToManyBehavior::className(),
                'relations' => [
                    'visible_attributes' => 'visibleAttributes',
                    'editable_attributes' => 'editableAttributes',
                ],
            ],
            [
                'class' => ActiveRecordHelper::className(),
                'cache' => 'cache',
            ],
        ];
    }

    /**
     * @return array [[DropDownList]]
     */
    public static function getRolesArray()
    {
        $cacheKey = 'RolesItems:' . implode(':', []) ;

        $items = Yii::$app->cache->get($cacheKey);
        if ($items !== false) {
            return $items;
        }

        $data = self::find()->select(['name'])->roles()->orderBy('name ASC')->asArray()->all();

        $cache_tags = [
            ActiveRecordHelper::getCommonTag(static::className()),
        ];

        foreach ($data as $val) {
            $key = $val['name'];
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
     * @return \yii\db\ActiveQuery
     */
    public function getVisibleAttributes() {
        return $this->hasMany(OrderAttribute::className(), ['attribute_name' => 'attribute_name'])
            ->viaTable('order_item_to_attribute_view', ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditableAttributes() {
        return $this->hasMany(OrderAttribute::className(), ['attribute_name' => 'attribute_name'])
            ->viaTable('order_item_to_attribute_edit', ['item_name' => 'name']);
    }

    /**
     * @return array [[DropDownList]]
     */
    public function getOrderAttributes($w_s = true) {
        return OrderAttribute::getAttributesArray($w_s);
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'name' => 'Name of Role',
            'visible_attributes' => 'Visible Attributes',
            'editable_attributes' => 'Editable Attributes',
        ];
    }

    /**
     * @param  array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find()->roles()->with('visibleAttributes');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC
                ]
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * @return [type] [description]
     */
    public static function tableName()
    {
        return \Yii::$app->authManager->itemTable;
    }

    /** @inheritdoc */
    public static function find()
    {
        return new AuthItemQuery(get_called_class());
    }

}
