<?php

use yii\db\Migration;
use yii\db\Schema;

class m170715_075621_create_logs extends Migration
{
    public function safeUp()
    {
        $this->createTable('logs', [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_INTEGER . '(5) NOT NULL DEFAULT 0 COMMENT "日志类型"',
            'type_id' => Schema::TYPE_INTEGER . '(255) NOT NULL DEFAULT 0  COMMENT "类型id"',
            'contents' => Schema::TYPE_TEXT . '  NOT NULL  DEFAULT "" COMMENT "日志内容"',
            'created_by' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT  "创建日志的用户"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170715_075621_create_logs cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170715_075621_create_logs cannot be reverted.\n";

        return false;
    }
    */
}
