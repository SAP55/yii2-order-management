<?php

namespace sap55\order\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use sap55\order\models\Store;
use sap55\order\models\search\StoreSearch;
use sap55\order\models\StoreGroup;
use sap55\order\models\OrderAttribute;




class StoreController extends Controller
{

    public $subData = [];

    public function beforeAction($event)
    {
        $this->setSubData();
        return parent::beforeAction($event);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'assign' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {

        $searchModel = new StoreSearch;
        $dataProvider = $searchModel->search($_GET);

        $subData['storeGroups'] = StoreGroup::getStoreGroupsArray();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'subData' => $this->subData
        ]);
    }


    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model,
            ]);
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Url action - /order/detail
     */
    public function actionDetail()
    {
        if (isset($_POST['expandRowKey'])) {
            return $this->actionView($_POST['expandRowKey']);
        } else {
            return '<div class="alert alert-danger">No data found</div>';
        }
    }


    public function actionCreate()
    {
        $model = new Store();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'The record was cteated.');

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'subData' => $this->subData
            ]);
        }
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'The record was updated.');

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'subData' => $this->subData
            ]);
        }
    }


    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
             $model->delete();
             Yii::$app->session->setFlash('success', 'The record was deleted.');
        } catch(\yii\db\IntegrityException $e) {
             // throw new \yii\web\ForbiddenHttpException('Could not delete this record.');
             Yii::$app->session->setFlash('warning', 'Could not delete this record.');
        }

        return $this->redirect(['index']);
    }

    protected function setSubData()
    {
        $this->subData['usersData'] = $this->module->getUsersArray();
        $this->subData['orderStatuses'] = $this->module->getOrderStatusesByUser(Yii::$app->user->id);
        $this->subData['orderAttributes'] = OrderAttribute::getAttributesArray();
        $this->subData['storeGroups'] = StoreGroup::getStoreGroupsArray();
    }


    protected function findModel($id)
    {
        if (($model = Store::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
