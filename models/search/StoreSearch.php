<?php

namespace sap55\order\models\search;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

use sap55\order\models\Store;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class StoreSearch extends Store
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['store_id', 'group_id'], 'integer'],
            [['store_id', 'group_id', 'name', 'url'], 'safe'],
        ];
    }

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
    public function search($params)
    {

        $query = Store::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'store_id' => $this->store_id,
            'group_id' => $this->group_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }

    protected function addCondition($query, $attribute, $partialMatch = false)
    {
        $value = $this->$attribute;
        if (trim($value) === '') {
            return;
        }
        if ($partialMatch) {
            $value = '%' . strtr($value, ['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']) . '%';
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
}

