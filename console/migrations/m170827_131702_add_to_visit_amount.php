<?php

use yii\db\Migration;
use yii\db\Schema;

class m170827_131702_add_to_visit_amount extends Migration
{
    public function safeUp()
    {
        $this->addColumn('visit_amount', 'interface', Schema::TYPE_STRING . '(255) COMMENT "接口地址"');
    }

    public function safeDown()
    {
        echo "m170827_131702_add_to_visit_amount cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170827_131702_add_to_visit_amount cannot be reverted.\n";

        return false;
    }
    */
}
