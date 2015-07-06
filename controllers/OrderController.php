<?php

namespace sap55\order\controllers;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use sap55\order\models\Order;
use sap55\order\models\search\OrderSearch;
use sap55\order\models\OrderStatus;
use sap55\order\models\PaymentStatus;
use sap55\order\models\PaymentMethod;
use sap55\order\models\ShippingMethod;
use sap55\order\models\OrderHistory;

use Rezzza\Formulate\Formula;


class OrderController extends Controller
{

		public $subData = [];

		public function behaviors()
		{
				return [
						'verbs' => [
								'class' => VerbFilter::className(),
								'actions' => [
				'delete' => ['post'],
								],
						],
				];
		}

		public function beforeAction($event)
		{
				$this->setSubData();
				return parent::beforeAction($event);
		}

		/**
		 * Lists all Order models.
		 * @return mixed
		 */
		public function actionIndex()
		{
			$this->setSubData();

			$searchModel = new OrderSearch();

			$dataProvider = $searchModel->search(Yii::$app->request->get(), [
				'attributes' => $this->subData['visibleAttributes'],
				'stores' => $this->subData['storesIds'],
			]);

			return $this->render('index', [
				'dataProvider' => $dataProvider,
				'searchModel' => $searchModel,
				'subData' => $this->subData
			]);
		}

		/**
		 * Displays a single Order model.
		 * @param integer $id
		 * @return mixed
		 */
		public function actionView($id)
		{
				$model = $this->findModel($id);

				$query = new Query();
				$orderHistory = new ActiveDataProvider([
						'query' => OrderHistory::find()
								->where([
										'order_id' => $model->order_id,
										'attribute' => 'order_status_id'
								]),
								'sort' => [
										'defaultOrder' => [
												// 'attribute' => SORT_ASC,
												'create_time' => SORT_ASC
										]
								],
								'pagination' => [
										'pageSize' => 25,
								],
				]);

				return $this->render('view', [
						'model' => $model,
						'orderHistory' => $orderHistory,
						'subData' => $this->subData
				]);
		}

		/**
		 * Url action - /order/detail
		 */
		public function actionDetail()
		{
				if (isset($_POST['expandRowKey'])) {
						$model = $this->findModel($_POST['expandRowKey']);

						$this->setSubData();

						return $this->renderAjax('_order_details', [
								'model' => $model,
								'subData' => $this->subData,
						]);
				} else {
						return '<div class="alert alert-danger">No data found</div>';
				}
		}

		public function actionEditable()
		{
				if (Yii::$app->request->post('hasEditable') && Yii::$app->request->post('Order')) {

						$posted = Yii::$app->request->post('Order');

						if ($id = Yii::$app->request->post('editableKey')) {

						} else {
								if ($posted['order_id']) {
										$id = $posted['order_id'];
								}
						}

						$model = $this->findModel($id);

						$this->setSubData();

						if (!ArrayHelper::isAssociative($posted, true)) {
								$post['Order'] = current($posted);
						} else {
								$post['Order'] = $posted;
						}

						$post['Order'] = $this->checkPermission($post['Order']);

						if ($model->load($post) && $model->save()) {
								echo \yii\helpers\Json::encode(['output' => $value, 'message' => '']);
						} else {
								echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
						}

						return;
				}
		}

		/**
		 * Displays a single Order model.
		 * @param integer $id
		 * @return mixed
		 */
		public function actionHistory($id)
		{
				$model = $this->findModel($id);

				$query = new Query();
				$orderHistory = new ActiveDataProvider([
						'query' => OrderHistory::find()->where([
								'order_id' => $model->order_id,
								// 'attribute' => 'order_status_id'
						]),
						'sort' => [
								'defaultOrder' => [
										// 'attribute' => SORT_ASC,
										'create_time' => SORT_ASC
								]
						],
						'pagination' => [
								'pageSize' => 20,
						],
				]);

				return $this->render('history', [
						'model' => $model,
						'orderHistory' => $orderHistory,
						'subData' => $this->subData
				]);
		}

		/**
		 * Creates a new Order model.
		 * If creation is successful, the browser will be redirected to the 'view' page.
		 * @return mixed
		 */
		public function actionCreate($store_id = null)
		{

				if ($store_id == null) {
						return $this->render('store_list', [
								'stores' => $this->subData['userStores']
						]);
				}

				$this->setSubData();


				$model = new Order();
				$model->scenario = 'create';
				$model->store_id = (int)$store_id;
				// $model->refresh();

				if ($model->load(Yii::$app->request->post()) && $model->save()) {
						$this->calculateFormula($model);

						Yii::$app->session->setFlash('success', 'The record was cteated.');

						return $this->redirect(['view', 'id' => $model->order_id]);
				} else {
						if (Yii::$app->request->isAjax) {
								return $this->renderAjax('_form', [
										'model' => $model,
										'subData' => $this->subData
								]);
						} else {
								return $this->render('create', [
										'model' => $model,
										'subData' => $this->subData
								]);
						}
				}
		}

		/**
		 * Updates an existing Order model.
		 * If update is successful, the browser will be redirected to the 'view' page.
		 * @param integer $id
		 * @return mixed
		 */
		public function actionUpdate($id)
		{
				$model = $this->findModel($id);

				$model->scenario = 'safeEdit';

				if ($model->load(Yii::$app->request->post()) && $model->save()) {
						$this->calculateFormula($model);

						Yii::$app->session->setFlash('success', 'The record was updated.');

						return $this->redirect(['view', 'id' => $model->order_id]);
				} else {
						return $this->render('update', [
								'model' => $model,
								'subData' => $this->subData
						]);
				}
		}

		/**
		 * Deletes an existing Order model.
		 * If deletion is successful, the browser will be redirected to the 'index' page.
		 * @param integer $id
		 * @return mixed
		 */
		public function actionDelete($id)
		{
				$model = $this->findModel($id);
				$model->status = Order::STATUS_DELETED;

				if($model->save()) {
						Yii::$app->session->setFlash('success', 'The record was deleted.');
				} else {
						Yii::$app->session->setFlash('error', 'The record was not deleted. Something going wrong');
				}

				return $this->redirect(['index']);
		}

		/**
		 * Finds the Order model based on its primary key value.
		 * If the model is not found, a 404 HTTP exception will be thrown.
		 * @param integer $id
		 * @return Order the loaded model
		 * @throws NotFoundHttpException if the model cannot be found
		 */
		protected function findModel($id)
		{
				$model = Order::find()
						->where([
								'order_id' => $id,
								'store_id' => array_keys($this->subData['userStores'])
						])->one();

				if ($model !== null) {
						return $model;
				} else {
						throw new NotFoundHttpException('The requested page does not exist.');
				}
		}

		protected function checkPermission($response)
		{
				foreach ($response as $key => $value) {
						if(!in_array($key, $this->subData['editableAttributes'])) {
								unset($response[$key]);
						}
				}

				return $response;
		}

		protected function setSubData()
		{
				$this->subData['visibleAttributes'] = $this->module->getVisiableAttributes();
				$this->subData['editableAttributes'] = $this->module->getEditableAttributes();
				$this->subData['systemAttributes'] = $this->module->getSystemAttributes();

				$this->subData['storesIds'] = $this->module->getStoresIdsByUser(Yii::$app->user->id);
				$this->subData['userStores'] = $this->module->getStoresByUser(Yii::$app->user->id);

				$this->subData['orderStatuses'] = $this->module->getOrderStatusesByUser(Yii::$app->user->id);
				$this->subData['orderStatusesHtml'] = OrderStatus::getOrderStatusHtmlArray();

				$this->subData['paymentStatusesList'] = PaymentStatus::getPaymentStatusArray();
				$this->subData['paymentStatusesHtml'] = PaymentStatus::getPaymentStatusHtmlArray();

				$this->subData['paymentMethods'] = PaymentMethod::getPaymentMethodArray();
				$this->subData['shippingMethods'] = ShippingMethod::getShippingMethodArray();
		}

		protected function calculateFormula($model)
		{
				if (!empty($model->store->formula_purchase_p) && !empty($model->purchase_price)) {
						$formula = new Formula($model->store->formula_purchase_p, Formula::CALCULABLE);
						$formula->setParameter('var_purchase_price', $model->purchase_price);
						$formula->setIsCalculable(true);
						if($formula->render()) {
								$model->sale_price = round($formula->render(), $model->store->round_purchase_p_precision, constant($model->store->round_purchase_p_mode));
								$model->total_price = $model->sale_price + $model->shipping_price;
								$model->update();
						}
				}
		}
}
