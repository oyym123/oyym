<?php

use yii\db\Migration;
use yii\db\Schema;

class m170711_122714_add_to_product extends Migration
{
    public function safeUp()
    {
        $this->addColumn('product', 'likes', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "点赞数量"');
        $this->addColumn('product', 'collections', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "收藏数量"');
    }

    public function safeDown()
    {
        echo "m170711_122714_add_to_product cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170711_122714_add_to_product cannot be reverted.\n";

        return false;
    }
    */
}
