<?php

namespace sap55\order\models\query;

use yii\rbac\Item;

class AuthItemQuery extends \yii\db\ActiveQuery
{
	public function Roles()
	{
		return $this->where(['type' => Item::TYPE_ROLE]);
	}
}