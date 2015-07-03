<?php

namespace sap55\order\models\search;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

use sap55\order\models\Order;
use sap55\order\models\OrderStatus;



/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Order
{
    
    private $c_time_start;
    private  $c_time_end;


    /**
     * @inheritdoc
     */
    // public function rules()
    // {
    //     return [
    //         [['order_id', 'store_id', 'create_by', 'payment_method_id', 'payment_status_id', 'shipping_method_id', 'status'], 'integer'],
    //         [['invoice_no', 'payment_text', 'shipping_text', 'shipping_tracking', 'comment', 'ip', 'forwarded_ip', 'user_agent', 'create_time', 'update_time'], 'safe'],
    //         [['purchase_price', 'shipping_price', 'total_price', 'sale_price'], 'number'],
    //         ['order_status_id', 'in', 'range' => OrderStatus::find()->select('order_status_id')->asArray()->column(), 'allowArray' => true],
    //         [['order_status_id'], 'exist', 'targetClass' => OrderStatus::className(), 'targetAttribute' => 'order_status_id'],
    //         [['order_status_id'], 'safe'],
    //     ];
    // }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return parent::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $data = [])
    {

        $query = Order::find()
            ->where([
                'store_id' => $data['stores'],
                'status' => Order::STATUS_ACTIVE
            ])
            ->with('store.orderStatuses', 'store.orderTemplate', 'store.group');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'create_time' => SORT_DESC
                ]
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['in', 'order_status_id', $this->order_status_id,]);

        $query->andFilterWhere([
            'order_id' => $this->order_id,
            'store_id' => $this->store_id,
            'create_by' => $this->create_by,
            'payment_method_id' => $this->payment_method_id,
            'payment_status_id' => $this->payment_status_id,
            'shipping_method_id' => $this->shipping_method_id,
            'purchase_price' => $this->purchase_price,
            'shipping_price' => $this->shipping_price,
            'total_price' => $this->total_price,
            'sale_price' => $this->sale_price,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'invoice_no', $this->invoice_no])
            ->andFilterWhere(['like', 'payment_text', $this->payment_text])
            ->andFilterWhere(['like', 'shipping_text', $this->shipping_text])
            ->andFilterWhere(['like', 'shipping_tracking', $this->shipping_tracking])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'forwarded_ip', $this->forwarded_ip])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent]);

        if(isset($this->create_time) && $this->create_time != '') {
            $c_filter = explode(' to ', $this->create_time);

            $this->c_time_start = Yii::$app->formatter->asDatetime($c_filter[0], "php:Y-m-d 00:00:00");
            $this->c_time_end = Yii::$app->formatter->asDatetime($c_filter[1], "php:Y-m-d 23:59:59");

            $query->andFilterWhere(['between', 'create_time', $this->c_time_start, $this->c_time_end]);
        }

        return $dataProvider;
    }
}

