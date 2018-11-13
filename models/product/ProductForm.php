<?php
/**
 * Created by PhpStorm.
 * User: wenceslaus
 * Date: 11/13/18
 * Time: 6:06 PM
 */

namespace app\models\product;


use app\models\brand\Brand;
use app\models\category\Category;
use yii\base\DynamicModel;

/**
 * Model ProductForm - form behind the Product Create/Update action.
 * @package app\models
 *
 * @property Category $category
 */
class ProductForm extends DynamicModel
{
    public $imageFile, $title, $description, $price, $amount, $category_id, $brand_id;
    public $loadCategory;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['amount', 'category_id', 'brand_id'], 'integer'],
            [['title'], 'string', 'max' => 32],
//            Length restriction may be redundant as descriptions is of text type.
            [['description'], 'string', 'max' => 255],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['title', 'description', 'price', 'amount'], 'required'],
            [['imageFile'], 'image'],
            [['loadCategory'], 'boolean'],
        ];
    }

    public function loadsCategory()
    {
        if ($this->loadCategory) {
            $this->loadCategory = false;
            return true;
        }
        return false;
    }

}