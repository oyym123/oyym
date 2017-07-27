<?php

use yii\db\Migration;
use yii\db\Schema;

class m170727_015238_add_to_user_info extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user_info', 'intro', Schema::TYPE_STRING . ' COMMENT "用户简介"');
    }

    public function safeDown()
    {
        echo "m170727_015238_add_to_user_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170727_015238_add_to_user_info cannot be reverted.\n";

        return false;
    }
    */
}
