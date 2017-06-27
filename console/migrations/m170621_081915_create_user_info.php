<?php

use yii\db\Migration;
use yii\db\Schema;

class m170621_081915_create_user_info extends Migration
{
    public function safeUp()
    {
        $this->createTable('user_info', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "用户ID"',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL COMMENT "用户昵称"',
            'profit' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0 COMMENT "用户收益"',
            'like_count' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "点赞人数"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170621_081915_create_user_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_081915_create_user_info cannot be reverted.\n";

        return false;
    }
    */
}
