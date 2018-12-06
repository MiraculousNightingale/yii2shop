<?php

namespace app\models\order;

use app\models\order\OrderItem;
use app\models\product\Product;
use app\models\user\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $description
 * @property int $status
 * @property int $delivery_type
 * @property string $destination
 * @property int $user_id
 * @property string $created_at
 *
 * @property User $user
 * @property OrderItem[] $items
 *
 * @property string $userName
 * @property string $userEmail
 *
 * @property int $totalCount
 * @property int $uniqueCount
 *
 * @property float $price
 */
class Order extends ActiveRecord
{
    const
        STATUS_CART = 0,
        STATUS_PENDING = 1,
        STATUS_COMPLETED = 2;

    const
        DELIVERY_SERVICE = 0,
        DELIVERY_MANUAL = 1;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'delivery_type', 'user_id'], 'integer'],
            [['description'], 'string', 'max' => 255],
            [['destination'], 'string', 'max' => 64],
            [['created_at'], 'string', 'max' => 16],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'status' => 'Status',
            'delivery_type' => 'Delivery Type',
            'destination' => 'Destination',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'uniqueCount' => 'Unique products',
            'totalCount' => 'Total products',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['created_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_CART => 'Cart',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    public static function getDeliveries()
    {
        return [
            self::DELIVERY_SERVICE => 'Service',
            self::DELIVERY_MANUAL => 'Manual',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->via('items');
    }

    public function getTotalCount()
    {
        $amount = 0;
        foreach ($this->items as $item) {
            $amount += $item->amount;
        }
        return $amount;
    }

    public function getUniqueCount()
    {
        return count($this->items);
    }

    public function getPrice()
    {
        $price = 0;
        foreach ($this->items as $item) {
            if($item->discountApplies()){
                $price += $item->getEndPrice();
            }else {
                $price += $item->price;
            }
        }
        return $price;
    }

    public function getStatusName()
    {
        return self::getStatuses()[$this->status];
    }

    public function getDeliveryName()
    {
        return self::getDeliveries()[$this->delivery_type];
    }

    public function getUserName()
    {
        return $this->user->username;
    }

    public function getUserEmail()
    {
        return $this->user->email;
    }

    public function isCart()
    {
        return $this->status == self::STATUS_CART;
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function isCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }

    /**
     * @param $productId
     * @return bool true | false Whether the product was added or is already in cart
     * @throws \Exception
     */
    public function addItem($productId)
    {
        if ($product = Product::findOne($productId)) {
            if ($this->hasItem($productId)) {
                return false;
            }
            $this->link('products', $product, ['amount' => 1]);
            return true;
        }
        throw new \Exception('Product could not be added.');
    }

    /**
     * @param $productId
     * @return bool
     * @throws \Exception
     */
    public function removeItem($productId)
    {
        if ($product = Product::findOne($productId)) {
            if ($this->hasItem($productId)) {
                $this->unlink('products', $product, true);
                return true;
            }
            return false;
        }
        throw new \Exception('Product could not be added.');
    }

    /**
     * @param $productId
     * @return bool Whether the order has a certain product
     */
    public function hasItem($productId)
    {
        return $this->getProducts()->where(['id' => $productId])->exists();
    }

    public function sendEmailNotification()
    {
        if ($this->isPending()) {
            return Yii::$app->mailer
                ->compose(['html' => 'order-notify-pending-html'], ['user' => $this->user, 'order' => $this])
                ->setTo($this->getUserEmail())
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject('Your order is pending!')
                ->send();
        }
        if ($this->isCompleted()) {
            return Yii::$app->mailer
                ->compose(['html' => 'order-notify-completed-html'], ['user' => $this->user, 'order' => $this])
                ->setTo($this->getUserEmail())
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject('Your order is completed!')
                ->send();
        }
        return false;
    }

}
