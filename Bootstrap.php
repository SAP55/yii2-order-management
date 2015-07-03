<?php

namespace sap55\order;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;
use yii\web\GroupUrlRule;
use yii\console\Application as ConsoleApplication;


class Bootstrap implements BootstrapInterface
{
		public function bootstrap($app)
		{
				if ($app->hasModule('order') && ($module = $app->getModule('order')) instanceof Module) {

						if ($app instanceof ConsoleApplication) {

						} else {
								$configUrlRule = [
										'prefix'  => $module->urlPrefix,
										'rules'	  => $module->urlRules,
								];

								if ($module->urlPrefix != 'order') {
										$configUrlRule['routePrefix'] = 'order';
								}

								$app->urlManager->addRules([new GroupUrlRule($configUrlRule)], false);
						}
				}

				if (!isset($app->get('i18n')->translations['order*'])) {
						$app->get('i18n')->translations['order*'] = [
								'class'		 => PhpMessageSource::className(),
								'basePath' => __DIR__ . '/messages',
						];
				}
		}
}
