<?php

namespace sap55\order\models\search;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use sap55\order\models\Order;
use sap55\order\models\OrderHistory;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderHistorySearch extends OrderHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'create_by'], 'integer'],
            [['create_time'], 'safe'],
            [['model', 'attribute', 'ip', 'user_agent'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = OrderHistory::find();

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

        $query->andFilterWhere([
            'record_id' => $this->record_id,
            'order_id' => $this->order_id,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'record_before' => $this->record_before,
            'record_after' => $this->record_after,
            'create_time' => $this->create_time,
        ]);

        return $dataProvider;
    }
}
