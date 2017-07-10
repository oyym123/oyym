<?php

use yii\db\Migration;
use yii\db\Schema;

class m170709_052209_create_account extends Migration
{
    public function safeUp()
    {
        $this->createTable('account', [
            'id' => Schema::TYPE_PK,
            'order_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "订单id"',
            'pay_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "订单id"',
            'type' => Schema::TYPE_SMALLINT . '(4) NOT NULL DEFAULT 0 COMMENT "1=支付2=收入3=退款"',
            'amount' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT "0.00" COMMENT "金额"',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 00 COMMENT "用户id"',
            'created_at' => Schema:: TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "创建时间"',
            'updated_at' => Schema:: TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "更新时间"'
        ]);
    }

    public function safeDown()
    {
        echo "m170709_052209_create_account cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170709_052209_create_account cannot be reverted.\n";

        return false;
    }
    */
}
