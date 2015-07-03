<?php

namespace sap55\order\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use sap55\order\models\StoreGroup;

/**
 * StoreGroupSearch represents the model behind the search form about StoreGroup.
 */
class StoreGroupSearch extends Model
{
	public $group_id;
	public $name;

	public function rules()
	{
		return [
			[['group_id'], 'integer'],
			[['name'], 'safe'],
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
		];
	}

	public function search($params)
	{
		$query = StoreGroup::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
            'group_id' => $this->group_id,
        ]);

		$query->andFilterWhere(['like', 'name', $this->name]);

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
