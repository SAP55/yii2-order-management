<?php

namespace sap55\order\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

use ruskid\YiiBehaviors\IpBehavior;

use app\models\User;

/**
 * This is the model class for table "order_history".
 *
 * @property integer $record_id
 * @property integer $order_id
 * @property string $model
 * @property string $attribute
 * @property string $record_before
 * @property string $record_after
 * @property string $create_time
 */
class OrderHistory extends \yii\db\ActiveRecord
{


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['create_time'],
                ],
                'value' => function () {
                    return new Expression('CURRENT_TIMESTAMP');
                }
            ],
            'blamea' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_by',
                'updatedByAttribute' => false,
            ],
            'ip' => [
                'class' => IpBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['ip'],
                ],
            ],
        ];
    }

    public function getRalationValue($id)
    {
        switch ($this->attribute) {
            case 'order_status_id':
                $value = OrderStatus::findOne($id);
                break;
            case 'store_id':
                $value = Store::findOne($id);
                break;
            case 'payment_method_id':
                $value = PaymentMethod::findOne($id);
                break;
            case 'payment_status_id':
                $value = PaymentStatus::findOne($id);
                break;
            case 'shipping_method_id':
                $value = ShippingMethod::findOne($id);
                break;
            // case 'order_status_id':
            //     $value = OrderStatus::findOne($id);
            //     break;
            // case 'order_status_id':
            //     $value = OrderStatus::findOne($id);
            //     break;
            // case 'order_status_id':
            //     $value = OrderStatus::findOne($id);
            //     break;
            
            default:
                # code...
                break;
        }

        if ($value !== null) {
            return $value->name;
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->user_agent = Yii::$app->request->userAgent;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'model', 'attribute'], 'required'],
            [['order_id', 'create_by'], 'integer'],
            // [['record_before', 'record_after'], 'string'],
            [['create_time'], 'safe'],
            [['model', 'attribute'], 'string', 'max' => 128],
            [['ip'], 'string', 'max' => 40],
            [['user_agent'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'record_id' => 'Record ID',
            'order_id' => 'Order ID',
            'model' => 'Model',
            'attribute' => 'Attribute',
            'record_before' => 'Record Before',
            'record_after' => 'Record After',
            'create_time' => 'Create Time',
            'create_by' => 'Create By',
            'ip' => 'Ip',
            'user_agent' => 'User Agent',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'create_by']);
    }
}
