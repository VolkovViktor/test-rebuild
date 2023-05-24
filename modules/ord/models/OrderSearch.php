<?php

namespace app\modules\ord\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ord\models\Order;

/**
 * OrderSearch represents the model behind the search form of `app\modules\ord\models\Order`.
 */
class OrderSearch extends Order
{
    public $count;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','service_id', 'user_id', 'status', 'mode'], 'integer'],
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
    {   $status = $params['status'];
        //var_dump($status);
        $searchQueryParams = [0 => 'orders.id', 1 => 'users.last_name', 2 => 'users.first_name', 3 => 'link'];
        $query = Order::find()->innerJoinWith('users', true)->innerJoinWith('services', true);
        if (isset($params['search_text'])) {
            $query->andFilterWhere(['like', $searchQueryParams[$params['search_attr']], $params['search_text']]);
        }
        if ($status != null) {
            $query->andFilterWhere(['status' => $status]);
        }
        $this->count = $query->count('*');

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

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

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
}
