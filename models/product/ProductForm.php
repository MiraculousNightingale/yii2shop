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
use yii\base\Model;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Model ProductForm - form behind the Product Create/Update action.
 * @package app\models
 *
 * @property Category $category
 */
class ProductForm extends Model
{
//    General
    public $imageFile, $title, $description, $price, $amount, $category_id, $brand_id;
//    Only on update
    public $source, $id, $imagePreview;
//    Utility
    public $loadCategory;

    /**
     * ProductForm constructor.
     * @param Product|null $product
     */
    public function __construct($product = null)
    {
        parent::__construct();
        if ($product) {
            $this->source = $product;
            $this->id = $product->id;
            $this->imagePreview = $product->image;
            $this->setAttributes($product->attributes, false);
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['price'], 'number'],
            [['amount', 'category_id', 'brand_id'], 'integer'],
            [['title'], 'string', 'max' => 32],
//            Length restriction may be redundant as descriptions is of text type.
            [['description'], 'string', 'max' => 255],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['title', 'description', 'price', 'amount'], 'required'],
            [['imageFile'], 'image'],
            [['imagePreview'], 'safe'],
            [['loadCategory'], 'boolean'],
        ];
    }

    /**
     * Perform a check if this form loads category features after user selected a category.
     * @return bool
     */
    public function loadsCategory()
    {
        if ($this->loadCategory) {
            $this->loadCategory = false;
            return true;
        }
        return false;
    }

    public function loadsSourceCategory()
    {
        if ($this->category_id == $this->source->category_id)
            return true;
        return false;
    }

    /**
     * @param ProductFeatureForm $featureForm
     * @param bool $runValidation
     * @return int|bool if saved successfully return saved product id, else return false
     */
    public function save($featureForm, $runValidation = true)
    {
        $product = new Product();
        $product->setAttributes($this->attributes);

        if ($imageFile = UploadedFile::getInstance($this, 'imageFile')) {
            $product->image = 'uploads/' . $imageFile->baseName . '.' . $imageFile->extension;
        }

        if ($product->save($runValidation) && $featureForm->save($product)) {
            if ($imageFile) $imageFile->saveAs($product->image);
            return $product->id;
        }
        return false;
    }

    /**
     * @param ProductFeatureForm $featureForm
     * @param bool $runValidation
     * @return bool|int
     */
    public function update($featureForm, $runValidation = true)
    {
        $product = $this->source;
        $product->setAttributes($this->attributes);

        if ($imageFile = UploadedFile::getInstance($this, 'imageFile')) {
            $product->image = 'uploads/' . $imageFile->baseName . '.' . $imageFile->extension;
        }

        if ($product->save($runValidation) && $featureForm->update($product)) {
            if ($imageFile) $imageFile->saveAs($product->image);
            return $product->id;
        }
        \Yii::$app->session->setFlash('danger', 'ProductForm Update failed.');
        return false;
    }

    /**
     * @return null|Category
     */
    public function getCategory()
    {
        return Category::findOne($this->category_id);
    }

}