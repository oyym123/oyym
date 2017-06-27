<?php

use yii\db\Migration;
use yii\db\Schema;

class m170621_071306_create_image extends Migration
{
    public function safeUp()
    {
        $this->createTable('image', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) COMMENT "图片名称"',
            'url' => Schema::TYPE_STRING . '(255)  COMMENT "图片链接地址"',
            'type' => Schema::TYPE_INTEGER . '(5) NOT NULL  COMMENT "图片类型"',
            'type_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "图片类型对应的id"',
            'size_type' => Schema::TYPE_INTEGER . '(3) NOT NULL DEFAULT 0 COMMENT "图片大小尺寸类型"',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "用户id"',
            'sort' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "图片排序"',
            'status' => Schema::TYPE_INTEGER . '(3) NOT NULL DEFAULT 1 COMMENT "状态"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170621_071306_create_image cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_071306_create_image cannot be reverted.\n";

        return false;
    }
    */
}
