<?php

namespace app\models\order;

use app\models\discount\Discount;
use app\models\product\Product;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $amount
 *
 * @property Order $order
 * @property Product $product
 *
 * @property float $price
 */
class OrderItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'amount'], 'integer'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
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
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getPrice()
    {
        return $this->amount * $this->product->price;
    }

    public function getEndPrice()
    {
        return $this->amount * $this->product->getEndPrice($this->order->user_id);
    }

    public function discountApplies()
    {
        /** @var Discount $discount */
        if ($discount = $this->order->user->getDiscountOn($this->product->category_id)) {
            if (strtotime($this->order->created_at) >= strtotime($discount->created_at)) {
                /*echo '<br>';
                echo 'Order: ' . strtotime($this->order->created_at) . '<br>';
                echo 'Discount: ' . strtotime($discount->created_at) . '<br>';
                if(strtotime($this->order->created_at) >= strtotime($discount->created_at))
                    echo 'Order was created later';
                else
                    echo 'Discount was created later';
                die;*/
                return $discount;
            }
        }
        return null;
    }
}
