<?php

namespace app\models\product;


use app\models\brand\Brand;
use app\models\category\Category;
use app\models\comment\Comment;
use app\models\feature\Feature;
use app\models\OrderItem;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property double $price
 * @property int $amount
 * @property int $category_id
 * @property int $brand_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property OrderItem[] $orderItems
 * @property Brand $brand
 * @property string $brandName
 * @property Category $category
 * @property string $categoryName
 * @property Feature[] $categoryFeatures
 * @property ProductFeature[] $features
 * @property Comment[] $comments
 *
 * @property string $image
 */
class Product extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['amount', 'category_id', 'brand_id'], 'integer'],
            [['title'], 'string', 'max' => 32],
//            Length restriction may be redundant as descriptions is of text type.
            [['description'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'string', 'max' => 16],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
//            Redundant? Read-only properties
//            [['brandName', 'categoryName'], 'safe'],
            [['title', 'description', 'price', 'amount'], 'required'],
            [['image'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'price' => 'Price',
            'amount' => 'Amount',
            'category_id' => 'Category ID',
            'brand_id' => 'Brand ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'brandName' => 'Brand',
            'categoryName' => 'Category',
            'imageUpload' => 'Image',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getBrandName()
    {
        return $this->brand->name;
    }

    public function getCategoryName()
    {
        return $this->category->name;
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeatures()
    {
        return $this->hasMany(ProductFeature::className(), ['product_id' => 'id']);
    }

    public function getFeaturesAsAttributes()
    {
        $features = [];
        foreach ($this->features as $feature) {
            $features[$feature->name] = $feature->value;
        }
        return $features;
    }

    /**
     * @return ActiveQuery
     */
    public function getCategoryFeatures()
    {
        return $this->hasMany(Feature::className(), ['id' => 'feature_id'])->via('features');
    }

    /**
     * @return array
     */
    public function getDetailedFeatures()
    {
        $detailed = [];
        foreach ($this->features as $feature)
            $detailed[] =
                [
                    'label' => $feature->name,
                    'value' => $feature->value,
                ];
        return $detailed;
    }

}
