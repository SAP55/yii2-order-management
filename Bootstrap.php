<?php

namespace sap55\order;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\i18n\PhpMessageSource;
use yii\web\IdentityInterface;
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

            // register auth manager
            if ($app->authManager === null) {
	            throw new InvalidConfigException('You should configure authManager component');
	        }

            if (!$this->checkSettingModuleInstalled($app)) {
	        	throw new InvalidConfigException('You should configure pheme\settings module');
            }

	        if (!$this->checkGridViewModuleInstalled($app)) {
	        	throw new InvalidConfigException('You should configure kartik\grid module');
            }

            if (!$this->checkAttachmentsModuleInstalled($app)) {
	        	throw new InvalidConfigException('You should configure nemmo\attachments module');
            }

	        // if (!$this->checkUserClassConfigured($app)) {
	        //     throw new InvalidConfigException('You should configure your user class instance of ActiveRecord');
	        // }
		}

		if (!isset($app->get('i18n')->translations['order*'])) {
			$app->get('i18n')->translations['order*'] = [
					'class'		 => PhpMessageSource::className(),
					'basePath' => __DIR__ . '/messages',
			];
		}
	}

    /**
     * Verifies that user class is instance of ActiveRecord.
     * @param  Application $app
     * @return bool
     */
    protected function checkUserClassConfigured(Application $app)
    {
        return $app->user->identityClass instanceof ActiveRecord;
    }

    /**
     * Verifies that kartik/grid module is installed and configured.
     * @param  Application $app
     * @return bool
     */
    protected function checkGridViewModuleInstalled(Application $app)
    {
        return $app->hasModule('gridview') && $app->getModule('gridview') instanceof \kartik\base\Module;
    }

    /**
     * Verifies that pheme/settings module is installed and configured.
     * @param  Application $app
     * @return bool
     */
    protected function checkSettingModuleInstalled(Application $app)
    {
        return $app->hasModule('settings');
    }

    /**
     * Verifies that nemmo\attachments module is installed and configured.
     * @param  Application $app
     * @return bool
     */
    protected function checkAttachmentsModuleInstalled(Application $app)
    {
        return $app->hasModule('attachments')  && $app->getModule('attachments') instanceof \nemmo\attachments\Module;
    }
}
