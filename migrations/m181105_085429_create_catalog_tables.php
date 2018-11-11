<?php

use yii\db\Migration;

/**
 * Handles the creation of catalog tables: `product`, `brand`, `category`, `feature`, `category_feature`.
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
            'description' => $this->text(),
            'price' => $this->float(2)->unsigned(),
            'amount' => $this->integer(11)->unsigned(),
            'category_id' => $this->integer(11)->unsigned(),
            'brand_id' => $this->integer(11)->unsigned(),
            'created_at' => $this->string(16),
            'updated_at' => $this->string(16),
        ]);

        $this->createTable('brand', [
            'id' => $this->primaryKey(11)->unsigned(),
            'name' => $this->string(32),
            'contact' => $this->string(64)
        ]);

        $this->createTable('category', [
            'id' => $this->primaryKey(11)->unsigned(),
            'name' => $this->string(32),
        ]);

        $this->createTable('feature', [
            'id' => $this->primaryKey(11)->unsigned(),
            'name' => $this->string(32),
            'value' => $this->string(16)
        ]);

        $this->createTable('category_feature', [
            'id' => $this->primaryKey(11)->unsigned(),
            'category_id' => $this->integer(11)->unsigned(),
            'feature_id' => $this->integer(11)->unsigned()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product');
        $this->dropTable('brand');
        $this->dropTable('category');
        $this->dropTable('feature');
        $this->dropTable('category_feature');
    }
}
