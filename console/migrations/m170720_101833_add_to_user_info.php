<?php

use yii\db\Migration;
use yii\db\Schema;

class m170720_101833_add_to_user_info extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user_info', 'attentions', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "被关注的数量"');
    }

    public function safeDown()
    {
        echo "m170720_101833_add_to_user_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170720_101833_add_to_user_info cannot be reverted.\n";

        return false;
    }
    */
}
