<?php

namespace app\models\order;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\order\Order;

/**
 * OrderSearch represents the model behind the search form of `app\models\order\Order`.
 */
class OrderSearch extends Order
{
    public $userName, $userEmail;
    public $uniqueCount, $totalCount;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'delivery_type', 'user_id'], 'integer'],
            [['description', 'destination', 'created_at'], 'safe'],
            [['userName', 'userEmail'], 'safe'],
            [['uniqueCount', 'totalCount'], 'integer'],
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
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'title',
                'userName' => [
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                    'label' => 'User Name',
                ],
                'userEmail' => [
                    'asc' => ['user.email' => SORT_ASC],
                    'desc' => ['user.email' => SORT_DESC],
                    'label' => 'User Email',
                ],
                'uniqueCount' => [
                    'asc' => ['COUNT(product.id)' => SORT_ASC],
                    'desc' => ['COUNT(product.id)' => SORT_DESC],
                    'label' => 'Unique Products',
                ],
                'totalCount' => [
                    'asc' => ['SUM(product.amount)' => SORT_ASC],
                    'desc' => ['SUM(product.amount)' => SORT_DESC],
                    'label' => 'Total Amount',
                ],
                'description',
                'price',
                'amount',
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
            'order.status' => $this->status,
            'delivery_type' => $this->delivery_type,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'destination', $this->destination])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);

        //related field for user name and email properties
        $query->joinWith('user');
        $query->andFilterWhere(['like', 'user.username', $this->userName]);
        $query->andFilterWhere(['like', 'user.email', $this->userEmail]);

        //filtration for related fields
        $query->joinWith('products');

        $query->groupBy('product.category_id');

        //related field for uniqueCount property
        if (is_numeric($this->uniqueCount))
            $query->andFilterHaving(['COUNT(product.id)' => $this->uniqueCount]);

        //related field for totalCount property
        if (is_numeric($this->totalCount))
            if ($this->totalCount == 0)
                $query->andFilterHaving(['COUNT(product.id)' => 0])->orFilterHaving(['SUM(product.amount)' => $this->totalCount]);
            else
                $query->andFilterHaving(['SUM(product.amount)' => $this->totalCount]);

        return $dataProvider;
    }
}
