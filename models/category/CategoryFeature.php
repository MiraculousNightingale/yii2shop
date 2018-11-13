<?php

namespace app\models\category;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category_feature".
 *
 * @property int $id
 * @property int $category_id
 * @property int $feature_id
 *
 * @property Category $category
 * @property Feature $feature
 */
class CategoryFeature extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_feature';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'feature_id'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['feature_id'], 'exist', 'skipOnError' => true, 'targetClass' => Feature::className(), 'targetAttribute' => ['feature_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'feature_id' => 'Feature ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeature()
    {
        return $this->hasOne(Feature::className(), ['id' => 'feature_id']);
    }
}
