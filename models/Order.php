<?php

namespace sap55\order\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use sap55\order\commands\OrderSendEmailCommand;

use ruskid\YiiBehaviors\IpBehavior;
use jlorente\command\db\Receiver;
use jlorente\command\behaviors\CommandGeneratorBehavior;

class Order extends \yii\db\ActiveRecord implements Receiver
{

    private $_oldattributes = array();
    private $fields = [
        'invoice_no',
        'order_status_id',
        'store_id',
        'payment_method_id',
        'payment_text',
        'payment_status_id',
        'shipping_method_id',
        'shipping_text',
        'purchase_price',
        'shipping_price',
        'total_price',
        'sale_price',
        'shipping_tracking',
        'comment',
    ];

    public $images;

    /** Status banned */
    const STATUS_BANNED = 0;
    /** Status active */
    const STATUS_ACTIVE = 1;
    /** Status deleted */
    const STATUS_DELETED = 2;


    private $statuses = [
        self::STATUS_DELETED => 'Deleted',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_BANNED => 'Suspended',
    ];

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    self::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                'value' => function () {
                    return new Expression('CURRENT_TIMESTAMP');
                }
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_by',
                'updatedByAttribute' => false,
            ],
            'attachments' => [
                'class' => \nemmo\attachments\behaviors\FileBehavior::className()
            ],
            // 'emailNotify' => [
            //     'class' => CommandGeneratorBehavior::className(),
            //     'commands' => [
            //         self::EVENT_BEFORE_UPDATE => OrderSendEmailCommand::className(),
            //     ],
            //     'condition' => function ($model) {
            //         return $model->isAttributeChanged('order_status_id');
            //     }
            // ],
            'ip' => [
                'class' => IpBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['ip'],
                ],
            ],
        ]);
    }

    public function sendEmailToUsers() {
        $view = 'notification';

        $mailer = Yii::$app->mailer;
        $mailer->viewPath = '@sap55/order/views/mail';
        $mailer->getView()->theme = \Yii::$app->view->theme;

        $store = Store::findOne($this->store_id);
        $emails = ArrayHelper::getColumn($store->users, 'email');

        if (($key = array_search(Yii::$app->user->identity->email, $emails)) !== false) {
            unset($emails[$key]);
        }

        // Yii::info(print_r($emails));

        $mailer->compose(['html' => $view, 'text' => 'text/' . $view], ['order' => $this])
            ->setFrom(isset(\Yii::$app->params['adminEmail']) ? \Yii::$app->params['adminEmail'] : 'no-reply@example.com')
            ->setTo($emails)
            ->setSubject('The Order Status has been updated.')
            ->send();
    }

    public function getImages()
    {
        if ($dataImages = $this->getFiles()) {

            foreach ($dataImages as $value) {
                $data[] = [
                    'url' => $value->getFpreviewUrl(),
                    'src' => $value->getPreviewUrl(),
                    'name' => $value['name'] . '.' . $value['type'],
                    'type' => $value->type,
                ];
            }

            return $data;
        } else {
            return false;
        }
    }

    public function trackOrder($oldattributes, $newattributes)
    {
        foreach ($this->fields as $field) {

            if (array_key_exists($field, $newattributes)) {

                $old_value = $oldattributes[$field];
                $new_value = $newattributes[$field];

                if (strcmp($old_value, $new_value) !== 0) {
                    $log = new OrderHistory;
                    $log->order_id = $this->getPrimaryKey();
                    $log->model = get_class($this);
                    $log->attribute = $field;
                    $log->record_before = $old_value;
                    $log->record_after = $new_value;
                    $log->save();

                    if ($field == 'order_status_id') $this->sendEmailToUsers();
                }
            }

        }
    }

    public function getOrderAttributes()
    {
        // foreach ($this->getAttributes() as $key => $value) {
        //     $orderAttributes[$key] = $this->getAttributeLabel($key);
        // }

        return $this->attributeLabels();
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['safeEdit'] = \Yii::$app->getModule('order')->getEditableAttributes();
        $scenarios['safeView'] = '*'; //\Yii::$app->getModule('order')->getVisiableAttributes();
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //Default values
            [['store_id', 'order_status_id', 'payment_status_id', 'shipping_method_id', 'payment_method_id'], 'default'],
            ['store_id', 'default', 'value' => Yii::$app->settings->get('orderDefaults.store_id')],
            ['order_status_id', 'default', 'value' => Yii::$app->settings->get('orderDefaults.order_status_id')],
            ['payment_status_id', 'default', 'value' => Yii::$app->settings->get('orderDefaults.payment_status_id')],
            ['shipping_method_id', 'default', 'value' => Yii::$app->settings->get('orderDefaults.shipping_method_id')],
            ['payment_method_id', 'default', 'value' => Yii::$app->settings->get('orderDefaults.PaymentMethod')],

            [['store_id', 'invoice_no'], 'required', 'on' => ['create', 'safeEdit']],

            ['store_id', 'in', 'range' => Store::find()->select('store_id')->asArray()->column()],
            ['payment_method_id', 'in', 'range' => PaymentMethod::find()->select('payment_method_id')->asArray()->column()],
            ['order_status_id', 'in', 'range' => OrderStatus::find()->select('order_status_id')->asArray()->column()],
            ['payment_status_id', 'in', 'range' => PaymentStatus::find()->select('payment_status_id')->asArray()->column()],
            ['shipping_method_id', 'in', 'range' => ShippingMethod::find()->select('shipping_method_id')->asArray()->column()],
            ['payment_method_id', 'in', 'range' => PaymentMethod::find()->select('payment_method_id')->asArray()->column()],

            // [['order_status_id'], 'safe'],
            [['order_status_id', 'create_by', 'status'], 'integer'],
            [['payment_text', 'shipping_text', 'comment'], 'string'],
            [['purchase_price', 'shipping_price', 'total_price', 'sale_price'], 'number'],
            [['create_time', 'update_time'], 'safe'],
            [['invoice_no'], 'string', 'max' => 128],
            [['shipping_tracking'], 'string', 'max' => 256],
            [['ip'], 'string', 'max' => 40],
            [['user_agent'], 'string', 'max' => 255],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id'          => 'Order ID',
            'invoice_no'        => 'Invoice No',
            'order_status_id'   => 'Order Status',
            'store_id'          => 'Store',
            'create_by'         => 'Create By',
            'payment_method_id' => 'Payment Method',
            'payment_text'      => 'Payment Text',
            'payment_status_id' => 'Payment Status',
            'shipping_method_id' => 'Shipping Method',
            'shipping_text'     => 'Shipping Text',
            'purchase_price'    => 'Purchase Price',
            'shipping_price'    => 'Shipping Price',
            'total_price'       => 'Total Price',
            'sale_price'        => 'Sale Price',
            'shipping_tracking' => 'Tracking Number',
            'comment'           => 'Comment',
            'ip'                => 'Ip',
            'forwarded_ip'      => 'Forwarded Ip',
            'user_agent'        => 'User Agent',
            'create_time'       => 'Create Time',
            'update_time'       => 'Update Time',
            'status'            => 'Status of Record',
            'images'            => 'Images',
        ];
    }

    public function getStatus($status = null)
    {
        if ($status === null) {
            return Yii::t('order', $this->statuses[$this->status]);
        }
        return Yii::t('order', $this->statuses[$status]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderHistory()
    {
        return $this->hasMany(OrderHistory::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'create_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['order_status_id' => 'order_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['payment_method_id' => 'payment_method_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentStatus()
    {
        return $this->hasOne(PaymentStatus::className(), ['payment_status_id' => 'payment_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShippingMethod()
    {
        return $this->hasOne(ShippingMethod::className(), ['shipping_method_id' => 'shipping_method_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['store_id' => 'store_id']);
    }

    public function getOrderTemplate()
    {
        return array_merge(ArrayHelper::getColumn($this->store->orderTemplate, 'attribute_name'), ['store_id' => 'store_id']);
    }

     /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (!$this->status) {
                    $this->status = self::STATUS_ACTIVE;
                }

                $this->user_agent = Yii::$app->request->userAgent;
                $this->forwarded_ip = Yii::$app->request->userHost;
            } else {
                // $this->trackOrder($this->getOldAttributes(), $this->getAttributes());
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert) {
            $this->trackOrder($this->getOldAttributes(), $this->getAttributes());
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        $this->setOldAttributes($this->getAttributes());
    }

    public function getOldAttributes()
    {
        return $this->_oldattributes;
    }

    public function setOldAttributes($value)
    {
        $this->_oldattributes = $value;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->order_id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return Url::to(['/order/order/view', 'id' => $this->order_id], true);
    }



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_item';
    }
}
