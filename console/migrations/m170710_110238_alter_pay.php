<?php

use yii\db\Migration;
use yii\db\Schema;

class m170710_110238_alter_pay extends Migration
{
    public function safeUp()
    {
        $this->addColumn('pay', 'account_type', Schema::TYPE_SMALLINT . '(4) NOT NULL DEFAULT 1 COMMENT "转账类型1=支付,2=退款,3=收入"');

        $this->dropColumn('order', 'user_id');
        $this->dropColumn('order', 'user_name');

        $this->addColumn('order', 'seller_id', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "卖家"');
        $this->addColumn('order', 'buyer_id', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "买家"');

        $this->createTable('order_product', [
            'id' => Schema::TYPE_PK,
            'order_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "订单号"',
            'pid' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "宝贝id"',
            'buyer_id' => Schema::TYPE_INTEGER . '(80) NOT NULL DEFAULT 0 COMMENT "买家id"',
            'seller_id' => Schema::TYPE_INTEGER . '(80) NOT NULL DEFAULT 0 COMMENT "卖家id"',
            'title' => Schema::TYPE_STRING . '(200) NOT NULL DEFAULT "" COMMENT "宝贝标题"',
            'count' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 1 COMMENT "购买数量"',
            'price' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT "0.00" COMMENT "购买价格"',
            'discount_price' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT "0.00" COMMENT "折扣价格"',
            'coupon_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "优惠券id"',
            'buy_type' => Schema::TYPE_SMALLINT . '(4) NOT NULL DEFAULT 0 COMMENT "购买类型:1=一口价2=单价"',
            'model_type' => Schema::TYPE_SMALLINT . ' COMMENT "json序列化其他信息"',
            'created_at' => Schema:: TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "创建时间"',
            'updated_at' => Schema:: TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "更新时间"'
        ]);

    }

    public function safeDown()
    {
        echo "m170710_110238_alter_pay cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170710_110238_alter_pay cannot be reverted.\n";

        return false;
    }
    */
}
