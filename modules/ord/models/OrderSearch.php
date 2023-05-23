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
    {
        $arr = [0 => 'id', 1 => 'user_id', 2 => 'link'];
        $query = Order::find()->innerJoinWith('users', true)->innerJoinWith('services', true);
        if (isset($params['search_text'])) {
            $query->andFilterWhere(['like', 'orders.' . $arr[$params['search_attr']], $params['search_text']]);
        }
        if (isset($params['status'])) {
            $query->andFilterWhere(['status' => $params['status']]);
        }
        $this->count = $query->count('*');

        // add conditions that should always apply here

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
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
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
