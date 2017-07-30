<?php

use yii\db\Migration;
use yii\db\Schema;

class m170729_093540_edit_order extends Migration
{
    public function safeUp()
    {
        $this->addColumn('order', 'seller_shipped_at', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "卖家发货时间"');
        $this->addColumn('order', 'buyer_shipped_at', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "买家发货时间"');
    }

    public function safeDown()
    {
        echo "m170729_093540_edit_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170729_093540_edit_order cannot be reverted.\n";

        return false;
    }
    */
}
