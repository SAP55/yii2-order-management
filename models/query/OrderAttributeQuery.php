<?php

namespace sap55\order\models\query;

use sap55\order\models\OrderAttribute;

class OrderAttributeQuery extends \yii\db\ActiveQuery
{
	public function system()
	{
		return $this->where(['assignment' => OrderAttribute::STATUS_SYSTEM]);
	}

	public function user()
	{
		return $this->where(['assignment' => OrderAttribute::STATUS_SYSTEM]);
	}
}