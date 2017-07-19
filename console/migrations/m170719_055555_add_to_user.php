<?php

use yii\db\Migration;
use yii\db\Schema;

class m170719_055555_add_to_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user', 'hx_password', Schema::TYPE_STRING . '(255) COMMENT "环信密码"');
    }

    public function safeDown()
    {
        echo "m170719_055555_add_to_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170719_055555_add_to_user cannot be reverted.\n";

        return false;
    }
    */
}
