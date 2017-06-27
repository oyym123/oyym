<?php

use yii\db\Migration;
use yii\db\Schema;

class m170623_065436_create_user_bought extends Migration
{
    public function safeUp()
    {
        $this->createTable('user_bought', [
            'id' => Schema::TYPE_PK,
            'product_id' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "产品ID"',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "用户ID"',
            'ip' => Schema::TYPE_STRING . '(255) COMMENT "ip地址"',
            'address_id' => Schema::TYPE_INTEGER . '(11) COMMENT "用户地址"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170623_065436_create_user_bought cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170623_065436_create_user_bought cannot be reverted.\n";

        return false;
    }
    */
}
