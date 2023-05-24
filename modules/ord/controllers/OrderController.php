<?php

namespace app\modules\ord\controllers;

use Yii;
use app\modules\ord\models\Order;
use app\modules\ord\models\OrderSearch;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\VerbFilter;


/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    /**
     * Lists all Order models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $filterParams = $this->actionGetSearchParams();
        $searchModel = $filterParams[0];
        $filterService = $filterParams[1];
        $viewService = $filterParams[2];
        $attr = $filterParams[3];
        $status = $filterParams[4];
        $dataProvider = $searchModel->search($attr);
        return $this->render('index', compact('searchModel', 'dataProvider', 'filterService', 'viewService', 'status'));
    }

    /**
     * Lists all Order models.
     *
     * @return array
     */
    public function actionGetSearchParams() {
        $searchModel = new OrderSearch();
        $attr = Yii::$app->request->get();
        $status = $attr['OrderSearch']['status'];
        $countAll = Order::find()->count('*');
        $countServices = (new Query())->select(['service_id','COUNT(*) as cnt'])->from('orders')->groupBy(['service_id'])->all(); //$countServices = Yii::$app->db->createCommand('SELECT service_id, COUNT(*) as cnt FROM orders GROUP BY service_id')->queryAll();

        $services = (new Query())->select(['id','name'])->from('services')->all();

        $filterService = [];
        $viewService = [];
        foreach ($services as $service) {
            $filterService[$service['id']] = '[' . $countServices[$service['id']-1]['cnt'] . '] ' . $service['name'];
            $viewService[$service['id']] = '<span style="border:1px #777777 solid;">' . $countServices[$service['id']-1]['cnt'] . ' </span>' . $service['name'];
        }

        array_unshift($filterService, ['' => 'All (' . $countAll . ')']);
        return array($searchModel, $filterService, $viewService, $attr, $status);
    }

}
