<?php
use yii\db\Schema;
use yii\db\Migration;

class m170715_073027_add_to_user_info extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user_info', 'sold_products', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "卖出的宝贝数量"');
    }

    public function safeDown()
    {
        echo "m170715_073027_add_to_user_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170715_073027_add_to_user_info cannot be reverted.\n";

        return false;
    }
    */
}
