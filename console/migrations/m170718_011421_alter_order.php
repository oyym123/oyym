<?php

use yii\db\Migration;
use yii\db\Schema;

class m170718_011421_alter_order extends Migration
{
    public function safeUp()
    {
        $this->addColumn('order', 'evaluation_status', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "评价状态,卖家已评价=1,买家已评价=2,双方已评价=3"');
    }

    public function safeDown()
    {
        echo "m170718_011421_alter_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170718_011421_alter_order cannot be reverted.\n";

        return false;
    }
    */
}
