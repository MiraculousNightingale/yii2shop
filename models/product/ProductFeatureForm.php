<?php
/**
 * Created by PhpStorm.
 * User: wenceslaus
 * Date: 11/13/18
 * Time: 7:44 PM
 */

namespace app\models\product;


use app\models\category\Category;
use yii\base\DynamicModel;

class ProductFeatureForm extends DynamicModel
{
    public function __construct($category_id = null, $config = [])
    {
        if ($category_id)
            parent::__construct(Category::findOne($category_id)->featureNames, $config);
        else
            parent::__construct(Category::find()->one()->featureNames, $config);
        $this->generateRules();
    }

    public function generateRules()
    {
        foreach ($this->attributes as $name => $value) {
            $this->addRule($name, 'string', ['max' => 32]);
            $this->addRule($name, 'required');
        }
    }
}