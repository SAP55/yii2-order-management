<?php

namespace sap55\order\models;

use Yii;

/**
 * This is the model class for table "order_permission".
 *
 * @property integer $user_role
 * @property string $field_view
 * @property string $field_edit
 */
class OrderPermission extends Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_permission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['field_view', 'field_edit', 'name'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Role Name',
            'field_view' => 'Field View',
            'field_edit' => 'Field Edit',
        ];
    }

    public function getRoles()
    {
        return $this->hasMany(\Yii::$app->authManager->getRoles(), ['name' => 'name']);
    }

    public function getAviabledFields() {
        return $this->hasMany(OrderStatus::className(), ['order_status_id' => 'order_status_id'])
            ->viaTable('status_to_store', ['store_id' => 'store_id']);
    }
}
