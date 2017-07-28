<?php

use yii\db\Migration;
use yii\db\Schema;

class m170727_075349_alter_order extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('order', 'shipping_company');
        $this->dropColumn('order', 'return_shipping_company');
        $this->addColumn('order', 'shipping_company', Schema::TYPE_STRING . ' COMMENT "快递公司拼音"');
        $this->addColumn('order', 'return_shipping_company', Schema::TYPE_STRING . ' COMMENT "退货快递公司拼音"');
    }

    public function safeDown()
    {
        echo "m170727_075349_alter_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170727_075349_alter_order cannot be reverted.\n";

        return false;
    }
    */
}
