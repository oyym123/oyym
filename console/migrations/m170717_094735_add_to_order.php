<?php
use yii\db\Schema;
use yii\db\Migration;

class m170717_094735_add_to_order extends Migration
{
    public function safeUp()
    {
        $this->addColumn('order', 'user_address', Schema::TYPE_STRING . '(255) COMMENT "用户地址"');
    }

    public function safeDown()
    {
        echo "m170717_094735_add_to_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170717_094735_add_to_order cannot be reverted.\n";

        return false;
    }
    */
}
