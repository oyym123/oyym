<?php

use yii\db\Migration;
use yii\db\Schema;

class m170621_124556_create_order extends Migration
{
    public function safeUp()
    {
        $this->createTable('order', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "下单用户ID"',
            'sn' => Schema::TYPE_STRING . '(100) NOT NULL  COMMENT "订单号"',
            'pay_type' => Schema::TYPE_INTEGER . '(4) NOT NULL  COMMENT "支付方式"',
            'pay_amount' => Schema::TYPE_DECIMAL . '(10,2) COMMENT "支付金额"',
            'product_amount' => Schema::TYPE_DECIMAL . '(10,2) COMMENT "产品的价格"',
            'discount_amount' => Schema::TYPE_DECIMAL . '(10,2) COMMENT "折扣后的价格"',
            'user_name' => Schema::TYPE_STRING . '(255) NOT NULL COMMENT "用户名称"',
            'status' => Schema::TYPE_INTEGER . '(4) NOT NULL COMMENT "评论状态"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170621_124556_create_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_124556_create_order cannot be reverted.\n";

        return false;
    }
    */
}
