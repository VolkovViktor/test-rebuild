<?php

namespace app\modules\ord\controllers;

use Yii;
use app\modules\ord\models\OrderSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index'  => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = '@app/modules/ord/views/layouts/main';
        $attr = Yii::$app->request->get();
        $searchModel = new OrderSearch();
        $searchModel->validate();
        $filterParams = $searchModel->getFilters($attr);
        $dataProvider = $searchModel->search($attr);
        return $this->render('index', compact('searchModel', 'dataProvider', 'filterParams'));
    }

}
