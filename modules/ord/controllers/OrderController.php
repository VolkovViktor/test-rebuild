<?php

namespace app\modules\ord\controllers;

use Yii;
use app\modules\ord\models\Order;
use app\modules\ord\models\OrderSearch;
use app\modules\ord\models\Services;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


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
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Order models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $countServices = (new Query())->select(['service_id','COUNT(*) as cnt'])->from('orders')->groupBy(['service_id'])->all(); //$countServices = Yii::$app->db->createCommand('SELECT service_id, COUNT(*) as cnt FROM orders GROUP BY service_id')->queryAll();

        $services = (new Query())->select(['id','name'])->from('services')->all();

        $filterService = [];
        $viewService = [];
        foreach ($services as $service) {
            $filterService[$service['id']] = '[' . $countServices[$service['id']-1]['cnt'] . '] ' . $service['name'];
            $viewService[$service['id']] = '<span style="border:1px #777777 solid;">' . $countServices[$service['id']-1]['cnt'] . ' </span>' . $service['name'];
        }



        array_map(
            'str_replace',            // callback function (str_replace)
            ['[', ']'], // first argument    ($search)
            ['<span style="border:1px #777777 solid;">', '</span>'], // second argument   ($replace)
            $viewService                    // third argument    ($subject)
        );

        return $this->render('index', compact('searchModel', 'dataProvider', 'countServices', 'filterService', 'viewService'));
    }

    public function actionSearch()
    {
        $attr = Yii::$app->request->get();
        $arr = [0 => 'id', 1 => 'user_id', 2 => 'link'];
        //var_dump($attr['search_text']);
        $query = OrderSearch::find()->where(['like', $arr[$attr['search_attr']], $attr['search_text']]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
        return $this->render('index', compact('dataProvider'));
    }

}
