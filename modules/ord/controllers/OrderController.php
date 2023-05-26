<?php

namespace app\modules\ord\controllers;

use Yii;
use app\modules\ord\models\OrderSearch;
use yii\web\Controller;
use yii\web\HttpException;
//var_dump(Yii::$app->request->resolve());

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
        $this->layout = '@app/modules/ord/views/layouts/main';
        $searchModel = new OrderSearch();
        $searchModel->validate();
        $attr = Yii::$app->request->get();
        $status = $attr['OrderSearch']['status'];
        $countAllOrders = $searchModel->getAllOrdersCount();
        $countServices = $searchModel->getCountServices();
        $services = $searchModel->getServices();
        $dataProvider = $searchModel->search($attr);
        return $this->render('index', compact('searchModel', 'dataProvider', 'services', 'countServices', 'countAllOrders', 'status'));
    }

}
