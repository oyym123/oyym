<?php
use yii\db\Schema;
use yii\db\Migration;

class m170715_065425_add_to_product extends Migration
{
    public function safeUp()
    {
        $this->addColumn('product', 'count_down', Schema::TYPE_INTEGER . '(11) COMMENT "开奖倒计时"');
    }

    public function safeDown()
    {
        echo "m170715_065425_add_to_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170715_065425_add_to_product cannot be reverted.\n";

        return false;
    }
    */
}
