<?php

namespace app\models\category;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use yii\db\ActiveQuery;

/**
 * CategorySearch represents the model behind the search form of `app\models\Category`.
 * @property int $productCount
 * @property int $productAmount
 */
class CategorySearch extends Category
{
    public $productCount;
    public $productAmount;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
            [['productAmount', 'productCount'], 'integer'],
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
        $query = Category::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'name',
                'productCount' => [
                    'asc' => ['COUNT(product.id)' => SORT_ASC],
                    'desc' => ['COUNT(product.id)' => SORT_DESC],
                    'label' => 'Unique Products',
                ],
                'productAmount' => [
                    'asc' => ['SUM(product.amount)' => SORT_ASC],
                    'desc' => ['SUM(product.amount)' => SORT_DESC],
                    'label' => 'Total Amount',
                ],
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        //filtration for related fields
        $query->joinWith('products');

        $query->groupBy('product.category_id');

        //related field for productCount property
        if (is_numeric($this->productCount))
        $query->andFilterHaving(['COUNT(product.id)' => $this->productCount]);

        //related field for productAmount property
        if (is_numeric($this->productAmount))
            //if searching for 0, and record's productCount==0 SQL will return nothing, so we need an explicit query
            if ($this->productAmount == 0)
                $query->andFilterHaving(['COUNT(product.id)' => 0])->orFilterHaving(['SUM(product.amount)' => $this->productAmount]);
            else
                $query->andFilterHaving(['SUM(product.amount)' => $this->productAmount]);

//                echo $query->createCommand()->rawSql; die;

        return $dataProvider;
    }
}
