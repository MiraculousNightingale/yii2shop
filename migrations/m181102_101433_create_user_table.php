<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m181102_101433_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(32),
            'password' => $this->string(32),
            'auth_key' => $this->string(16),
            'access_key' => $this->string(16),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
