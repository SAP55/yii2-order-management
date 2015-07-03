<?php

namespace sap55\order\models\query;

class StoreQuery extends \yii\db\ActiveQuery
{
	public function own($stores_id, $full_access = false)
	{
		$this->andWhere('store_id = :store_id', [
			':store_id' => $stores_id
		]);
		return $this;
	}
}