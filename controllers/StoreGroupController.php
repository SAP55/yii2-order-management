<?php

namespace sap55\order\controllers;

use sap55\order\models\StoreGroup;
use sap55\order\models\search\StoreGroupSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * GroupController implements the CRUD actions for StoreGroup model.
 */
class StoreGroupController extends Controller
{
	/**
	 * Lists all StoreGroup models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new StoreGroupSearch;
		$dataProvider = $searchModel->search($_GET);

        Url::remember();
		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single StoreGroup model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($group_id)
	{
        Url::remember();
        return $this->render('view', [
			'model' => $this->findModel($group_id),
		]);
	}

	/**
	 * Creates a new StoreGroup model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new StoreGroup;

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

	/**
	 * Updates an existing StoreGroup model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($group_id)
	{
		$model = $this->findModel($group_id);

		if ($model->load($_POST) && $model->save()) {
            return $this->redirect(Url::previous());
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing StoreGroup model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($group_id)
	{
		$this->findModel($group_id)->delete();
		return $this->redirect(Url::previous());
	}

	/**
	 * Finds the StoreGroup model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return StoreGroup the loaded model
	 * @throws HttpException if the model cannot be found
	 */
	protected function findModel($group_id)
	{
		if (($model = StoreGroup::findOne($group_id)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested page does not exist.');
		}
	}
}
