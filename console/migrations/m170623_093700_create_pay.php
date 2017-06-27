<?php

use yii\db\Migration;
use yii\db\Schema;

class m170623_093700_create_pay extends Migration
{
    public function safeUp()
    {
        $this->createTable('pay', [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "支付种类"',
            'type_id' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "支付种类对应的ID"',
            'pay_type' => Schema::TYPE_INTEGER . '(4) COMMENT "支付方式"',
            'status' => Schema::TYPE_STRING . '(45) COMMENT "支付状态"',
            'out_trade_no' => Schema::TYPE_STRING . '(45) COMMENT "返回的流水号"',
            'out_trade_status' => Schema::TYPE_STRING . '(45) COMMENT "支付状态中文显示"',
            'log' => Schema::TYPE_TEXT . ' COMMENT "支付状态"',
            'weixin_pay_xml' => Schema::TYPE_TEXT . ' COMMENT "微信支付返回值"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170623_093700_create_pay cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170623_093700_create_pay cannot be reverted.\n";

        return false;
    }
    */
}
