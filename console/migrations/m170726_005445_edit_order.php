<?php

use yii\db\Migration;
use yii\db\Schema;

class m170726_005445_edit_order extends Migration
{
    public function safeUp()
    {
        $this->addColumn('order', 'deleted_at', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "删除时间"');
    }

    public function safeDown()
    {
        echo "m170726_005445_edit_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170726_005445_edit_order cannot be reverted.\n";

        return false;
    }
    */
}
