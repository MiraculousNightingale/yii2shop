<?php

namespace app\models\product;

use app\models\feature\Feature;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product_feature".
 *
 * @property int $id
 * @property int $product_id
 * @property int $feature_id
 * @property string $value
 *
 * @property Feature $feature
 * @property Product $product
 * @property string $name
 */
class ProductFeature extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_feature';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'feature_id'], 'integer'],
            [['value'], 'string', 'max' => 16],
            [['feature_id'], 'exist', 'skipOnError' => true, 'targetClass' => Feature::className(), 'targetAttribute' => ['feature_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'feature_id' => 'Feature ID',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeature()
    {
        return $this->hasOne(Feature::className(), ['id' => 'feature_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return string Feature name
     */
    public function getName()
    {
        return $this->feature->name;
    }
}
