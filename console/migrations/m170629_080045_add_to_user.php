<?php

use yii\db\Migration;
use yii\db\Schema;

class m170629_080045_add_to_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user', 'token', Schema::TYPE_INTEGER . '(11) COMMENT "获取用户的token"');
        $this->addColumn('user', 'login_type', Schema::TYPE_INTEGER . '(3)  DEFAULT 0 COMMENT "登入类型"');
    }

    public function safeDown()
    {
        echo "m170629_080045_add_to_user cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170629_080045_add_to_user cannot be reverted.\n";

        return false;
    }
    */
}
