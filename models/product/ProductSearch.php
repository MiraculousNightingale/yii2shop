<?php

namespace app\models\product;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\product\Product;
use yii\db\ActiveQuery;

/**
 * ProductSearch represents the model behind the search form of `app\models\Product`.
 * @property string $brandName
 * @property string $categoryName
 */
class ProductSearch extends Product
{
    /**
     * {@inheritdoc}
     */
    public $brandName;
    public $categoryName;

    public function rules()
    {
        return [
            [['id', 'amount', 'category_id', 'brand_id'], 'integer'],
            [['title', 'description', 'created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
            [['brandName', 'categoryName'], 'safe'],
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
        $query = Product::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'title',
                'brandName' => [
                    'asc' => ['brand.name' => SORT_ASC],
                    'desc' => ['brand.name' => SORT_DESC],
                    'label' => 'Brand',
                ],
                'categoryName' => [
                    'asc' => ['category.name' => SORT_ASC],
                    'desc' => ['category.name' => SORT_DESC],
                    'label' => 'Category',
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
            'price' => $this->price,
            'amount' => $this->amount,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

//      related field for brandName property
        $query->joinWith(['brand' => function ($q) {
            /** @var ActiveQuery $q */
            $q->where(['like', 'brand.name', isset($this->brandName) ? $this->brandName : '']);
        }]);

//      related field for categoryName property
        $query->joinWith(['category' => function ($q) {
            /** @var ActiveQuery $q */
            $q->where(['like', 'category.name', isset($this->categoryName) ? $this->categoryName : '']);
        }]);

        return $dataProvider;
    }
}
