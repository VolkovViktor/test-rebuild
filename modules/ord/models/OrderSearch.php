<?php

namespace app\modules\ord\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\HttpException;

/**
 * OrderSearch represents the model behind the search form of `app\modules\ord\models\Order`.
 */
class OrderSearch extends Order
{
    const STATUS_PENDING = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELED = 4;
    const STATUS_ERROR = 0;

    const MODE_MANUAL = 0;
    const MODE_AUTO = 1;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','service_id', 'user_id', 'status', 'mode'], 'integer'],
            [['status'], 'in', 'range' => array_keys($this->getStatuses())],
            [['mode'], 'in', 'range' => array_keys($this->getModes())],
            [['link'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);

        if (!$this->validate()) {
            throw new HttpException(400);
        }

        $status = $params['status'];
        $searchQueryParams = [0 => 'orders.id', 1 => 'users.last_name', 2 => 'users.first_name', 3 => 'link'];

        $query = Order::find()->innerJoinWith('users', true)->innerJoinWith('services', true);
        if (isset($params['search_text'])) {
            $query->andFilterWhere(['like', $searchQueryParams[$params['search_attr']], $params['search_text']]);
        }

        if ($status != null) {
            $query->andFilterWhere(['status' => $status]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'quantity' => $this->quantity,
            'service_id' => $this->service_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'mode' => $this->mode,
        ]);

        $query->andFilterWhere(['like', 'link', $this->link]);

        return $dataProvider;
    }

    /**
     * List statuces.
     *
     * @return array
     */
    public function getStatuses()
    {
        return [
            '' => 'All Orders',
            static::STATUS_PENDING => 'Pending',
            static::STATUS_IN_PROGRESS => 'In progress',
            static::STATUS_COMPLETED => 'Completed',
            static::STATUS_CANCELED => 'Canceled',
            static::STATUS_ERROR => 'Error',
        ];
    }

    /**
     * List modes.
     *
     * @return array
     */
    public function getModes()
    {
        return [
            '' => 'All',
            static::MODE_MANUAL => 'Manual',
            static::MODE_AUTO => 'Auto',
        ];
    }

    /**
     * All count orders.
     *
     * @return integer
     */
    public function getAllOrdersCount() {
        return (new Query())->select(['COUNT(*) as cnt'])->from('orders')->all()[0]['cnt'];
    }

    /**
     * List counts services.
     *
     * @return array
     */
    public function getCountServices() {
        return (new Query())->select(['service_id','COUNT(*) as cnt'])->from('orders')->groupBy(['service_id'])->all(); // alter command: Yii::$app->db->createCommand('SELECT service_id, COUNT(*) as cnt FROM orders GROUP BY service_id')->queryAll();
    }

    /**
     * List services.
     *
     * @return array
     */
    public function getServices() {
        return (new Query())->select(['id','name'])->from('services')->all();
    }

    /**
     * Get data for filters and views.
     *
     * @param array $params
     *
     * @return array
     */
    public function getFilters($attr) {
        $status = $attr['OrderSearch']['status'];
        $countAllOrders = $this->getAllOrdersCount();
        $countServices = $this->getCountServices();
        $services = $this->getServices();
        $filterService = [];
        $viewService = [];
        foreach ($services as $service) {
            $filterService[$service['id']] = '[' . $countServices[$service['id']-1]['cnt'] . '] ' . $service['name'];
            $viewService[$service['id']] = '<span style="border:1px #777777 solid;">' . $countServices[$service['id']-1]['cnt'] . ' </span>' . $service['name'];
        }
        array_unshift($filterService, ['' => 'All (' . $countAllOrders . ')']);

        return [
                    'modes' => $this->getModes(),
                    'statuses' => $this->getStatuses(),
                    'status' => $status,
                    'filterService' => $filterService,
                    'viewService' => $viewService,
               ];
    }
}
