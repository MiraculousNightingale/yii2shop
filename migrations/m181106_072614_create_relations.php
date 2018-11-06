<?php

use yii\db\Migration;

/**
 * Handles the creation of relations
 *
 * Table `category_trait` has foreign keys to the tables:
 * - `category`
 * - `trait`
 *
 * Table `order_item` has foreign keys to the tables:
 * - `order`
 * - `product`
 *
 * Table `order` has foreign key to the table:
 * - `user`
 *
 * Table `product` has foreign key to the table:
 * - `category`
 */
class m181106_072614_create_relations extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for column `order_id` in table `order_item`
        $this->createIndex(
            'idx-order_item-order_id',
            'order_item',
            'order_id'
        );

        //add foreign key for table `order`
        $this->addForeignKey(
            'fk-order_item-order_id',
            'order_item',
            'order_id',
            'order',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `product_it` in table `order_item`
        $this->createIndex(
            'idx-order_item-product_id',
            'order_item',
            'product_id'
        );

        //add foreign key for table `product`
        $this->addForeignKey(
            'fk-order_item-product_id',
            'order_item',
            'product_id',
            'product',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `user_id` in table `order`
        $this->createIndex(
            'idx-order-user_id',
            'order',
            'user_id'
        );

        //add foreign key for table `user`
        $this->addForeignKey(
            'fk-order-user_id',
            'order',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `category_id` in table `product`
        $this->createIndex(
            'idx-product-category_id',
            'product',
            'category_id'
        );

        //add foreign key for table `category`
        $this->addForeignKey(
            'fk-product-category_id',
            'product',
            'category_id',
            'category',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `category_id` in table `category_trait`
        $this->createIndex(
            'idx-category_trait-category_id',
            'category_trait',
            'category_id'
        );

        //add foreign key for table `category`
        $this->addForeignKey(
            'fk-category_trait-category_id',
            'category_trait',
            'category_id',
            'category',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `trait_id` in table `category_trait`
        $this->createIndex(
            'idx-category_trait-trait_id',
            'category_trait',
            'trait_id'
        );

        //add foreign key for table `trait`
        $this->addForeignKey(
            'fk-category_trait-trait_id',
            'category_trait',
            'trait_id',
            'trait',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-order_item-product_id',
            'order_item'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-order_item-product_id',
            'order_item'
        );

        // drops foreign key for table `order`
        $this->dropForeignKey(
            'fk-order_item-order_id',
            'order_item'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            'idx-order_item-order_id',
            'order_item'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-order-user_id',
            'order'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-order-user_id',
            'order'
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-product-category_id',
            'product'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-product-category_id',
            'product'
        );

        // drops foreign key for table `trait`
        $this->dropForeignKey(
            'fk-category_trait-trait_id',
            'category_trait'
        );

        // drops index for column `trait_id`
        $this->dropIndex(
            'idx-category_trait-trait_id',
            'category_trait'
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-category_trait-category_id',
            'category_trait'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-category_trait-category_id',
            'category_trait'
        );
    }
}
