<?php

use yii\db\Migration;

class m170713_023715_alter_product extends Migration
{
    public function safeUp()
    {
        $this->addColumn('product', 'order_award_count', Schema::TYPE_INTEGER . '(11) COMMENT "参与人次"');
        $this->addColumn('product', 'deleted_at', Schema::TYPE_INTEGER . '(11) COMMENT "删除于"');
        $this->dropColumn('product', 'unit', Schema::TYPE_INTEGER . '(11) COMMENT "删除于"');
        $this->dropColumn('product', 'user_id', Schema::TYPE_INTEGER . '(11) COMMENT "删除于"');
    }

    public function safeDown()
    {
        echo "m170713_023715_alter_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170713_023715_alter_product cannot be reverted.\n";

        return false;
    }
    */
}
