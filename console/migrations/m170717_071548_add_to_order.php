<?php
use yii\db\Schema;
use yii\db\Migration;

class m170717_071548_add_to_order extends Migration
{
    public function safeUp()
    {
        $this->addColumn('order', 'ip', Schema::TYPE_STRING . '(16) COMMENT "用户IP地址"');
    }

    public function safeDown()
    {
        echo "m170717_071548_add_to_order cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170717_071548_add_to_order cannot be reverted.\n";

        return false;
    }
    */
}
