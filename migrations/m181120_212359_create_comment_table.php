<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `user`
 */
class m181120_212359_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(11)->unsigned(),
            'user_id' => $this->integer(11)->unsigned(),
            'content' => $this->text(),
            'created_at' => $this->string(16),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-comment-product_id',
            'comment',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-comment-product_id',
            'comment',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-comment-user_id',
            'comment',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-comment-user_id',
            'comment',
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
            'fk-comment-product_id',
            'comment'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-comment-product_id',
            'comment'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-comment-user_id',
            'comment'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-comment-user_id',
            'comment'
        );

        $this->dropTable('comment');
    }
}
