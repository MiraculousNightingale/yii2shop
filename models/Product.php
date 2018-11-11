<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
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
            [['brandName', 'categoryName'], 'safe'],
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

}
