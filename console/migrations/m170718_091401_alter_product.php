<?php

use yii\db\Migration;
use yii\db\Schema;

class m170718_091401_alter_product extends Migration
{
    public function safeUp()
    {
        $this->addColumn('product', 'progress', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "众筹进度,若有值保留4位"');
    }

    public function safeDown()
    {
        echo "m170718_091401_alter_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170718_091401_alter_product cannot be reverted.\n";

        return false;
    }
    */
}
