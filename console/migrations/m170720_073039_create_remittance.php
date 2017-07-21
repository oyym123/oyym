<?php

use yii\db\Migration;
use yii\db\Schema;

class m170720_073039_create_remittance extends Migration
{
    public function safeUp()
    {
        $this->createTable('remittance', [
            'id' => Schema::TYPE_PK,
            'from_account_type' => Schema::TYPE_INTEGER . '(5) NOT NULL DEFAULT 0 COMMENT "公司的方式1=通过公司支付宝汇款;2=通过公司微信汇款"',
            'from_account_params' => Schema::TYPE_TEXT . ' NOT NULL COMMENT "扣款账户所需要用到的参数serialize格式"',
            'to_account_type' => Schema::TYPE_INTEGER . '(5) NOT NULL DEFAULT 0 COMMENT "用户的收款账户类型1=支付宝;2=微信"',
            'to_account_params' => Schema::TYPE_TEXT . '(5) NOT NULL COMMENT "收款账户所需要用到的参数serialize格式"',
            'type' => Schema::TYPE_INTEGER . '(5) NOT NULL DEFAULT 0 COMMENT "汇款类型:1=参与宝贝退款;2=卖出宝贝获得收益"',
            'status' => Schema::TYPE_INTEGER . '  NOT NULL  DEFAULT 0 COMMENT "0=等待转账; 1=转账中; 2=转账成功 3=转账失败"',
            'amount' => Schema::TYPE_DECIMAL . '(10,2)  NOT NULL  DEFAULT 0 COMMENT "转账金额"',
            'order_id' => Schema::TYPE_INTEGER . '  NOT NULL  DEFAULT 0 COMMENT "订单id"',
            'user_id' => Schema::TYPE_INTEGER . '  NOT NULL  DEFAULT 0 COMMENT "用户id"',
            'out_trade_no' => Schema::TYPE_STRING . '(50)  NOT NULL  DEFAULT "" COMMENT "返回的外部流水号"',
            'out_trade_status' => Schema::TYPE_STRING . '(50)  NOT NULL  DEFAULT "" COMMENT "支付状态中文显示"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "更新时间"',
        ]);

        $this->dropColumn('pay', 'account_type');
        $this->addColumn('account', 'remittance_id', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "汇款id"');
    }

    public function safeDown()
    {
        echo "m170720_073039_create_remittance cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170720_073039_create_remittance cannot be reverted.\n";

        return false;
    }
    */
}
