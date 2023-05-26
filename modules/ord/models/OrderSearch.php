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
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','service_id', 'user_id', 'status', 'mode'], 'integer'],
            [['status'], 'validateStatus'],
            [['mode'], 'validateMode'],
            [['link'], 'safe'],
        ];
    }

    /**
     * Rule for status.
     *
     * @param integer
     */
    public function validateStatus($attribute, $params)
    {
        if (!in_array($this->$attribute, [0, 1, 2, 3, 4])) {
            $this->addError($attribute, 'Parameter "status" do not valid.');
        }
    }

    /**
     * Rule for mode.
     *
     * @param integer
     */
    public function validateMode($attribute, $params)
    {
        if (!in_array($this->$attribute, [0, 1])) {
            $this->addError($attribute, 'Parameter "mode" do not valid.');
        }
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
}
