<?php

use yii\db\Migration;

/**
 * Handles the creation of table `rating`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `user`
 */
class m181120_071654_create_rating_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('rating', [
            'id' => $this->primaryKey()->unsigned(),
            'product_id' => $this->integer(11)->unsigned(),
            'user_id' => $this->integer(11)->unsigned(),
            'value' => $this->integer(11)->unsigned(),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-rating-product_id',
            'rating',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-rating-product_id',
            'rating',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-rating-user_id',
            'rating',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-rating-user_id',
            'rating',
            'user_id',
            'user',
            'id',
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
            'fk-rating-product_id',
            'rating'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-rating-product_id',
            'rating'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-rating-user_id',
            'rating'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-rating-user_id',
            'rating'
        );

        $this->dropTable('rating');
    }
}
