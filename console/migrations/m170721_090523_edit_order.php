<?php

use yii\db\Migration;
use yii\db\Schema;

class m170721_090523_edit_order extends Migration
{
    public function safeUp()
    {
        $this->addColumn('order', 'freight', Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0 COMMENT "运费"');
    }

    public function safeDown()
    {
        echo "m170721_090523_edit_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170721_090523_edit_order cannot be reverted.\n";

        return false;
    }
    */
}
