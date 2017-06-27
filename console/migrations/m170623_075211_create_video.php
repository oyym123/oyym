<?php

use yii\db\Migration;
use yii\db\Schema;

class m170623_075211_create_video extends Migration
{
    public function safeUp()
    {
        $this->createTable('video', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) COMMENT "视频名称"',
            'url' => Schema::TYPE_STRING . '(255)  COMMENT "视频链接地址"',
            'type' => Schema::TYPE_INTEGER . '(5) NOT NULL  COMMENT "视频类型"',
            'type_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "视频类型对应的id"',
            'size_type' => Schema::TYPE_INTEGER . '(3) NOT NULL DEFAULT 0 COMMENT "视频对应分辨率类型"',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "用户id"',
            'sort' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "图片排序"',
            'status' => Schema::TYPE_INTEGER . '(3) NOT NULL DEFAULT 1 COMMENT "状态"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170623_075211_create_video cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170623_075211_create_video cannot be reverted.\n";

        return false;
    }
    */
}
