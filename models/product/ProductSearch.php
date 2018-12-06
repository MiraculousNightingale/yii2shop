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
 * @property float $fromPrice
 * @property float $toPrice
 */
class ProductSearch extends Product
{
    /**
     * {@inheritdoc}
     */
    public $brandName;
    public $categoryName;
    public $fromPrice, $toPrice;
    public $fromRating, $toRating;

    //used on index view
    public $sortBrand, $sortTitle, $sortPrice, $sortRating;

    const
        SORT_NONE = 0,
        SORT_ASC = 1,
        SORT_DESC = 2;

    public function rules()
    {
        return [
            [['id', 'amount', 'category_id', 'brand_id'], 'integer'],
            [['title', 'description', 'created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
            [['brandName', 'categoryName'], 'safe'],
            [['fromPrice', 'toPrice', 'fromRating', 'toRating'], 'number'],
            [['sortBrand', 'sortTitle', 'sortPrice', 'sortRating'], 'integer'],
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
     * @return $this|array
     */

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
                'totalRating' => [
                    'asc' => ['AVG(rating.value)' => SORT_ASC],
                    'desc' => ['AVG(rating.value)' => SORT_DESC],
                    'label' => 'Rating',
                ],
            ],
        ]);

        $this->load($params);

        // explicit sorting for index listView of products
        if ($this->sortBrand != self::SORT_NONE)
            if ($this->sortBrand == self::SORT_ASC) {
                $dataProvider->getSort()->setAttributeOrders(['brandName' => SORT_ASC]);
            } elseif ($this->sortBrand == self::SORT_DESC) {
                $dataProvider->getSort()->setAttributeOrders(['brandName' => SORT_DESC]);
            }

        if ($this->sortTitle != self::SORT_NONE)
            if ($this->sortTitle == self::SORT_ASC) {
                $dataProvider->getSort()->setAttributeOrders(['title' => SORT_ASC]);
            } elseif ($this->sortTitle == self::SORT_DESC) {
                $dataProvider->getSort()->setAttributeOrders(['title' => SORT_DESC]);
            }

        if ($this->sortPrice != self::SORT_NONE)
            if ($this->sortPrice == self::SORT_ASC) {
                $dataProvider->getSort()->setAttributeOrders(['price' => SORT_ASC]);
            } elseif ($this->sortPrice == self::SORT_DESC) {
                $dataProvider->getSort()->setAttributeOrders(['price' => SORT_DESC]);
            }

        if ($this->sortRating != self::SORT_NONE)
            if ($this->sortRating == self::SORT_ASC) {
                $dataProvider->getSort()->setAttributeOrders(['totalRating' => SORT_ASC]);
            } elseif ($this->sortRating == self::SORT_DESC) {
                $dataProvider->getSort()->setAttributeOrders(['totalRating' => SORT_DESC]);
            }

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

        $query->andFilterWhere(['>=', 'price', $this->fromPrice])
            ->andFilterWhere(['<=', 'price', $this->toPrice]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        //related field for brandName property
        $query->joinWith('brand');
        $query->andFilterWhere(['like', 'brand.name', $this->brandName]);


        //related field for categoryName property
        $query->joinWith('category');
        $query->andFilterWhere(['like', 'category.name', $this->categoryName]);

        //related field for rating property
        $query->joinWith('ratings');
        $query->andFilterHaving(['>=', 'AVG(rating.value)', $this->fromRating])
            ->andFilterHaving(['<=', 'AVG(rating.value)', $this->toRating]);

        $query->groupBy('product.id');

        return $dataProvider;
    }

}
