<?php
/**
 * Created by PhpStorm.
 * User: wenceslaus
 * Date: 11/13/18
 * Time: 7:44 PM
 */

namespace app\models\product;


use app\models\category\Category;
use app\models\feature\Feature;
use yii\base\DynamicModel;

class ProductFeatureForm extends DynamicModel
{
    /**
     * ProductFeatureForm constructor.
     * @param Category|null $category
     * @param Product|null $product
     * @param array $config
     * @throws \Exception
     */
    public function __construct($category = null, $product = null, $config = [])
    {
        if ($category) {
            parent::__construct($category->getFeatureNames(), $config);
            if ($product) {
                if ($product->category_id == $category->id) {
                    $this->setAttributes($product->getFeaturesAsAttributes(), false);
                } else {
                    throw new \Exception('Given category is not the same as given product\'s category!');
                }
            }
        } else {
            parent::__construct(Category::find()->one()->getFeatureNames(), $config);
        }
        $this->generateRules();
    }

    public function generateRules()
    {
        foreach ($this->attributes as $name => $value) {
            $this->addRule($name, 'string', ['max' => 32]);
            $this->addRule($name, 'required');
        }
    }

    /**
     * @param Product $product
     * @return bool succession of procedure
     */
    public function save($product)
    {
        try {
            foreach ($this->attributes as $name => $value) {
                $product->link('categoryFeatures', Feature::find()->where(['name' => $name])->one(), ['value' => $value]);
//            TODO: Remove debug code.
//            echo $name . ' ' . $value . '<br>';
            }
//            die;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param Product $product
     * @return bool succession of procedure
     */
    public
    function update($product)
    {
        try {
            foreach ($product->getFeaturesAsAttributes() as $name => $value) {
                $product->unlink('categoryFeatures', Feature::find()->where(['name' => $name])->one(), true);
                echo $name . ' ' . $value . '<br>';
            }
//        TODO: Remove debug code. 2
//        foreach ($this->attributes as $name => $value) {
//            $product->link('categoryFeatures', Feature::find()->where(['name' => $name])->one(), ['value' => $value]);
//            echo $name . ' ' . $value . '<br>';
//        }
//            die;
            $this->save($product);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}