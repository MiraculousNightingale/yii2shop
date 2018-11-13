<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property double $price
 * @property string $description
 * @property int $status
 * @property int $delivery_type
 * @property string $destination
 * @property int $user_id
 * @property string $created_at
 *
 * @property User $user
 * @property OrderItem[] $orderItems
 * @property Product[] $items
 */
class Order extends ActiveRecord
{
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
            [['price'], 'number'],
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
            'price' => 'Price',
            'description' => 'Description',
            'status' => 'Status',
            'delivery_type' => 'Delivery Type',
            'destination' => 'Destination',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
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
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    public function getItems()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->via('orderItems');
    }
}
