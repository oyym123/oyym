<?php

use yii\db\Migration;
use yii\db\Schema;

class m170727_075249_edit_order extends Migration
{
    public function safeUp()
    {
        $this->addColumn('order', 'shipping_company', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "快递公司id"');
        $this->addColumn('order', 'shipping_number', Schema::TYPE_STRING . '(30) NOT NULL DEFAULT 0 COMMENT "快递运单号"');
        $this->addColumn('order', 'return_shipping_company', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "退货快递公司id"');
        $this->addColumn('order', 'return_shipping_number', Schema::TYPE_STRING . '(30) NOT NULL DEFAULT 0 COMMENT "退货快递运单号"');
    }

    public function safeDown()
    {
        echo "m170727_075249_edit_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170727_075249_edit_order cannot be reverted.\n";

        return false;
    }
    */
}
