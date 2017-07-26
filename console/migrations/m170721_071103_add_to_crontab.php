<?php

use yii\db\Migration;
use yii\db\Schema;

class m170721_071103_add_to_crontab extends Migration
{
    public function safeUp()
    {
        $this->addColumn('crontab', 'log', Schema::TYPE_TEXT . ' COMMENT "日志"');
    }

    public function safeDown()
    {
        echo "m170721_071103_add_to_crontab cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170721_071103_add_to_crontab cannot be reverted.\n";

        return false;
    }
    */
}
