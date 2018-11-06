<?php

use yii\db\Migration;

/**
 * Handles the creation of catalog tables: `product`, `category`, `trait`, `category_trait`.
 */
class m181105_085429_create_catalog_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(11)->unsigned(),
            'title' => $this->string(32),
            'description' => $this->string(255),
            'price' => $this->float(2)->unsigned(),
            'amount' => $this->integer(11)->unsigned(),
            'category_id' => $this->integer(11)->unsigned(),
            'create_at' => $this->string(16),
            'update_at' => $this->string(16),
        ]);

        $this->createTable('category', [
            'id' => $this->primaryKey(11)->unsigned(),
            'name' => $this->string(32),
        ]);

        $this->createTable('trait', [
            'id' => $this->primaryKey(11)->unsigned(),
            'name' => $this->string(32),
            'value' => $this->string(16)
        ]);

        $this->createTable('category_trait', [
            'id' => $this->primaryKey(11)->unsigned(),
            'category_id' => $this->integer(11)->unsigned(),
            'trait_id' => $this->integer(11)->unsigned()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product');
        $this->dropTable('category');
        $this->dropTable('trait');
        $this->dropTable('category_trait');
    }
}
