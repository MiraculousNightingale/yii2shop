<?php
/**
 * Created by PhpStorm.
 * User: wenceslaus
 * Date: 11/24/18
 * Time: 6:33 PM
 */

namespace app\models\order;


use yii\base\Model;

class OrderForm extends Model
{
    public $description, $delivery_type, $destination;
    public $loadDestination;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_type'], 'integer'],
            [['description'], 'string', 'max' => 255],
            [['destination'], 'string', 'max' => 64],
            [['loadDestination'], 'boolean'],
        ];
    }

    public function loadsDestination()
    {
        if ($this->loadDestination) {
            $this->loadDestination = false;
            return true;
        }
        return false;
    }


    /**
     * @param $order Order to which we apply form data.
     * @return bool
     */
    public function save($order)
    {
        $order->setAttributes($this->attributes);
        $order->status=Order::STATUS_PENDING;
        return $order->save();
    }

}