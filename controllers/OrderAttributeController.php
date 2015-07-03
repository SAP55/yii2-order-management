<?php

namespace sap55\order\controllers;

use sap55\order\models\AuthItem as Role;
use sap55\order\models\Order;
use sap55\order\models\OrderAttribute;
use sap55\order\models\search\OrderAttributeSearch;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\rbac\Item;

/**
 * OrderAttributeController implements the CRUD actions for OrderAttribute model.
 */
class OrderAttributeController extends Controller
{
	/**
	 * Lists all OrderAttribute models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new OrderAttributeSearch;
		$dataProvider = $searchModel->search($_GET);

        $subData['statuses'] = $searchModel->getStatuses();

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
			'subData' => $subData,
		]);
	}

	/**
	 * Creates a new OrderAttribute model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new OrderAttribute;

		try {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(Url::previous());
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
            $model->addError('_exception', $msg);
		}
        return $this->render('create', ['model' => $model,]);
	}

	public function actionInstall()
	{
		// $model = new OrderAttribute;
		// $model->deleteAll();

		foreach ($this->getOrderAttributes() as $key => $value) {
			if (OrderAttribute::findOne($key) == null) {
				$model = new OrderAttribute;
				$model->attribute_name = $key;
				$model->save(false);
			}
		}

		return $this->redirect(Url::to('index'));
	}

	public function actionEditable()
    {

        if (Yii::$app->request->post('hasEditable') && Yii::$app->request->post('OrderAttribute')) {

            $posted = Yii::$app->request->post('OrderAttribute');

            if ($id = Yii::$app->request->post('editableKey')) {

            } else {
                if ($posted['attribute_name']) {
                    $id = $posted['attribute_name'];
                }
            }

            $model = $this->findModel($id);

            if (!ArrayHelper::isAssociative($posted, true)) {
                $post['OrderAttribute'] = current($posted);
            } else {
                $post['OrderAttribute'] = $posted;
            }

            if ($model->load($post) && $model->save()) {
                echo \yii\helpers\Json::encode(['output' => $value, 'message' => '']);
            } else {
                echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
            }

            return;
        }
    }

	/**
	 * Finds the OrderAttribute model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return OrderAttribute the loaded model
	 * @throws HttpException if the model cannot be found
	 */
	protected function findModel($attribute_name)
	{
		if (($model = OrderAttribute::findOne($attribute_name)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested page does not exist.');
		}
	}

	protected function getOrderAttributes()
	{
		$model = new Order;

    	return $model->getOrderAttributes();
	}
}
