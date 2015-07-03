<?php

namespace app\modules\order\models;

use jlorente\notification\models\Notifier;


/**
* 
*/
class Ntf extends Notifier
{
	public function getNotifierGeneratorClass() {
		return true;
	}

	public function execute()
	{
		return true;
	}
}