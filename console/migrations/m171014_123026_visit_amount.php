<?php

use yii\db\Migration;
use yii\db\Schema;

class m171014_123026_visit_amount extends Migration
{
    public function safeUp()
    {
        $this->createTable('visit_amount', [
            'id' => Schema::TYPE_PK,
            'user_ip' => Schema::TYPE_STRING . '(50) NOT NULL DEFAULT 0 COMMENT "用户ip地址"',
            'user_ip_address' => Schema::TYPE_STRING . '(255) COMMENT "用户ip地址位置"',
            'phone_type' => Schema::TYPE_INTEGER . '(3) NOT NULL DEFAULT 0 COMMENT "2=安卓,1=ios"',
            'visit_times' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "访问次数"',
            'created_at' => Schema::TYPE_INTEGER . '(11) COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m171014_123026_visit_amount cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171014_123026_visit_amount cannot be reverted.\n";

        return false;
    }
    */
}
