<?php

use yii\db\Migration;
use yii\db\Schema;

class m170710_070758_alter_to_comments extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('comments', 'sort', Schema::TYPE_INTEGER . '(3) COMMENT "排序"');
        $this->alterColumn('comments', 'status', Schema::TYPE_INTEGER . '(3) NOT NULL DEFAULT 0 COMMENT "状态"');
    }

    public function safeDown()
    {
        echo "m170710_070758_alter_to_comments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170710_070758_alter_to_comments cannot be reverted.\n";

        return false;
    }
    */
}
