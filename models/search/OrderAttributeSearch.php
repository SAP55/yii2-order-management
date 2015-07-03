<?php

namespace sap55\order\models\search;

use yii\data\ActiveDataProvider;
use sap55\order\models\OrderAttribute;

/**
 * OrderAttributeSearch represents the model behind the search form about OrderAttribute.
 */
class OrderAttributeSearch extends OrderAttribute
{
	public $attribute_name;
	public $assignment;

	public function rules()
	{
		return [
			[['attribute_name'], 'safe'],
			[['assignment'], 'integer'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'attribute_name' => 'Attribute Name',
			'assignment' => 'Assignment',
		];
	}

	public function search($params)
	{
		$query = OrderAttribute::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
            'assignment' => $this->assignment,
        ]);

		$query->andFilterWhere(['like', 'attribute_name', $this->attribute_name]);

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
