<?php

namespace app\models\discount;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\discount\Discount;

/**
 * DiscountSearch represents the model behind the search form of `app\models\discount\Discount`.
 */
class DiscountSearch extends Discount
{
    public $categoryName;
    public $userName, $userEmail;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'user_id', 'percent'], 'integer'],
            [['userName', 'userEmail'], 'safe'],
            [['categoryName'], 'safe'],
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
        $query = Discount::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'categoryName' => [
                    'asc'=>['category.name'=>SORT_ASC],
                    'desc'=>['category.name'=>SORT_DESC],
                    'label'=> 'Category Name',
                ],
                'userName'=>[
                    'asc'=>['user.username'=>SORT_ASC],
                    'desc'=>['user.username'=>SORT_DESC],
                    'label'=> 'User Name',
                ],
                'userEmail'=>[
                    'asc'=>['user.email'=>SORT_ASC],
                    'desc'=>['user.email'=>SORT_DESC],
                    'label'=> 'User Email',
                ],
                'percent',
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
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'percent' => $this->percent,
        ]);

        $query->joinWith('category');
        $query->andFilterWhere(['like','category.name', $this->categoryName]);

        $query->joinWith('user');
        $query->andFilterWhere(['like', 'user.username', $this->userName]);
        $query->andFilterWhere(['like', 'user.email', $this->userEmail]);

        return $dataProvider;
    }
}
