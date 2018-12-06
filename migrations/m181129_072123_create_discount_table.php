<?php

use yii\db\Migration;

/**
 * Handles the creation of table `discount`.
 * Has foreign keys to the tables:
 *
 * - `category`
 * - `user`
 */
class m181129_072123_create_discount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('discount', [
            'id' => $this->primaryKey()->unsigned(),
            'category_id' => $this->integer(11)->unsigned(),
            'user_id' => $this->integer(11)->unsigned(),
            'percent' => $this->integer(11)->unsigned(),
            'created_at' => $this->string(16),
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            'idx-discount-category_id',
            'discount',
            'category_id'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-discount-category_id',
            'discount',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-discount-user_id',
            'discount',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-discount-user_id',
            'discount',
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
        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-discount-category_id',
            'discount'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-discount-category_id',
            'discount'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-discount-user_id',
            'discount'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-discount-user_id',
            'discount'
        );

        $this->dropTable('discount');
    }
}
