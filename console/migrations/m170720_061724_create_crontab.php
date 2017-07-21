<?php

use yii\db\Migration;
use yii\db\Schema;

class m170720_061724_create_crontab extends Migration
{
    public function safeUp()
    {
        $this->createTable('crontab', [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_INTEGER . '(5) NOT NULL DEFAULT 0 COMMENT "计划任务分类,根据分类执行不同的任务"',
            'status' => Schema::TYPE_INTEGER . '  NOT NULL  DEFAULT 0 COMMENT "计划任务执行状态0=待执行 1=执行成功 2=执行失败"',
            'exec_count' => Schema::TYPE_INTEGER . '  NOT NULL  DEFAULT 0 COMMENT "计划任务执行次数"',
            'exec_max_count' => Schema::TYPE_INTEGER . '  NOT NULL  DEFAULT 0 COMMENT "计划任务执行失败后,允许执行的最大次数"',
            'exec_start_time' => Schema::TYPE_INTEGER . '  NOT NULL  DEFAULT 0 COMMENT "计划任务执行开始时间"',
            'params' => Schema::TYPE_TEXT . '  NOT NULL COMMENT "计划任务执行所需要的参数serialize格式"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170720_061724_create_crontab cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170720_061724_create_crontab cannot be reverted.\n";

        return false;
    }
    */
}
