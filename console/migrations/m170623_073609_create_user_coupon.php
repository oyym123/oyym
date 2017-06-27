<?php

use yii\db\Migration;
use yii\db\Schema;

class m170623_073609_create_user_coupon extends Migration
{
    public function safeUp()
    {
        $this->createTable('user_coupon', [
            'id' => Schema::TYPE_PK,
            'status' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "优惠券状态"',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "用户ID"',
            'coupon_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "优惠券ID"',
            'order_id' => Schema::TYPE_STRING . '(255) NOT NULL  COMMENT "优惠券名称"',
            'sort' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "排序"',
            'used_at' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "使用的地方"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170623_073609_create_user_coupon cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170623_073609_create_user_coupon cannot be reverted.\n";

        return false;
    }
    */
}
