<?php

namespace app\models\category;


use app\models\feature\Feature;
use app\models\product\Product;
use voskobovich\behaviors\ManyToManyBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 *
 * @property CategoryFeature[] $categoryFeatures
 * @property Product[] $products
 * @property Feature[] $features
 *
 * @property int $productCount
 * @property int $productAmount
 *
 * Relational m2m property
 * @property $feature_ids
 *
 * @property string $featureString
 */
class Category extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 32],
//            Redundant? Read-only properties
//            [['productAmount', 'productCount'], 'integer'],
            [['feature_ids'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'productCount' => 'Unique Products',
            'productAmount' => 'Total Amount',
            'featureList' => 'Features',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => ManyToManyBehavior::className(),
                'relations' => [
                    'feature_ids' => 'features',
                ],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryFeatures()
    {
        return $this->hasMany(CategoryFeature::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    /**
     * @return int Unique product count
     */
    public function getProductCount()
    {
        return count($this->products);
    }

    /**
     * @return int Total amount of products
     */
    public function getProductAmount()
    {
        $amount = 0;
        foreach ($this->products as $product) {
            $amount += $product->amount;
        }
        return $amount;
    }

    /**
     * @return ActiveQuery
     */
    public function getFeatures()
    {
        return $this->hasMany(Feature::className(), ['id' => 'feature_id'])->via('categoryFeatures');
    }

    /**
     * @return string Line of feature names.
     */
    public function getFeatureString()
    {
        $list = '';
        foreach ($this->features as $feature)
            $list .= $feature->name . ' | ';
        return $list;
    }

    /**
     * @return string[] Array of feature names
     */
    public function getFeatureNames()
    {
        $names=[];
        foreach ($this->features as $feature)
            $names[]=$feature->name;
        return $names;
    }

}
