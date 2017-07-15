<?php

use yii\db\Migration;

class m170714_071609_alter_product extends Migration
{
    public function safeUp()
    {
        $this->addColumn('product', 'order_id', Schema::TYPE_INTEGER . '(11) COMMENT "中奖订单id"');
    }

    public function safeDown()
    {
        echo "m170714_071609_alter_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170714_071609_alter_product cannot be reverted.\n";

        return false;
    }
    */
}
