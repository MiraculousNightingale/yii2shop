<?php

namespace app\models\feature;

use app\models\category\CategoryFeature;
use app\models\product\ProductFeature;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "feature".
 *
 * @property int $id
 * @property string $name
 *
 * @property CategoryFeature[] $categoryFeatures
 * @property ProductFeature[] $productFeatures
 */
class Feature extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feature';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 32],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryFeatures()
    {
        return $this->hasMany(CategoryFeature::className(), ['feature_id' => 'id']);
    }

    public function getProductFeatures()
    {
        return $this->hasMany(ProductFeature::className(), ['feature_id' => 'id']);
    }

}
