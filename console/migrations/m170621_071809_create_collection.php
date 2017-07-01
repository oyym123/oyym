<?php

use yii\db\Migration;
use yii\db\Schema;

class m170621_071809_create_collection extends Migration
{
    public function safeUp()
    {
        $this->createTable('collection', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "收藏者ID"',
            'type_id' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "收藏的类型ID"',
            'type' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "收藏的类型"',
            'status' => Schema::TYPE_INTEGER . '(4) NOT NULL COMMENT "收藏状态"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170621_071809_create_collection cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_071809_create_collection cannot be reverted.\n";

        return false;
    }
    */
}
