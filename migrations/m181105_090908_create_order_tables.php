<?php

use yii\db\Migration;

/**
 * Handles the creation of tables `order` and `order_item`.
 */
class m181105_090908_create_order_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(11)->unsigned(),
            'price' => $this->float(2)->unsigned(),
            'description' => $this->string(255),
            'status' => $this->tinyInteger(1)->unsigned()->notNull()->defaultValue(0),
            'delivery_type' => $this->tinyInteger(1)->unsigned()->notNull()->defaultValue(0),
            'destination' => $this->string(64),
            'user_id' => $this->integer(11)->unsigned(),
            'created_at' => $this->string(16)
        ]);

        $this->createTable('order_item', [
            'id' => $this->primaryKey(11)->unsigned(),
            'order_id' => $this->integer(11)->unsigned(),
            'product_id' => $this->integer(11)->unsigned(),
            'amount' => $this->integer(11)->unsigned()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order_item');
        $this->dropTable('order');
    }
}
