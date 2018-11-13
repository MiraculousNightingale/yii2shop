<?php

namespace app\models\brand;

use app\models\product\Product;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "brand".
 *
 * @property int $id
 * @property string $name
 * @property string $contact
 *
 * @property Product[] $products
 */
class Brand extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 32],
            [['contact'], 'string', 'max' => 64],
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
            'contact' => 'Contact',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['brand_id' => 'id']);
    }

}
