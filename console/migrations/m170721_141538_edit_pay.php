<?php

use yii\db\Migration;
use yii\db\Schema;

class m170721_141538_edit_pay extends Migration
{
    public function safeUp()
    {
        $this->addColumn('pay', 'order_id', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "订单id"');
        $this->addColumn('pay', 'sn', Schema::TYPE_STRING . '(30) NOT NULL DEFAULT "" COMMENT "订单号"');
        $this->addColumn('pay', 'paid_at', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "支付时间"');
        $this->dropColumn('pay', 'type');
        $this->dropColumn('pay', 'type_id');
    }

    public function safeDown()
    {
        echo "m170721_141538_edit_pay cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170721_141538_edit_pay cannot be reverted.\n";

        return false;
    }
    */
}
