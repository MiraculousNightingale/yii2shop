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
            'id' => $this->primaryKey(11)->unsigned(),
            'email'=>$this->string(64),
            'username' => $this->string(32),
            'password' => $this->string(32),
            'auth_key' => $this->string(32),
            'access_token' => $this->string(32),
            'verification_token' => $this->string(32),
            'status' => $this->tinyInteger(1)->unsigned()->notNull()->defaultValue(0),
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
