<?php

use yii\db\Migration;
use yii\db\Schema;

class m170724_075426_create_city3 extends Migration
{
    public function safeUp()
    {
        $this->dropTable('city');
        $this->createTable('city', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(50) NOT NULL DEFAULT 0 COMMENT "城市名称"',
            'level' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0  COMMENT "等级"',
            'upid' => Schema::TYPE_INTEGER . '  NOT NULL  DEFAULT 0 COMMENT "父级id"',
            'sort_by_pinyin' => Schema::TYPE_STRING . '(100) COMMENT "拼音排序"',
            'created_at' => Schema::TYPE_INTEGER . '(11) COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170724_075426_create_city cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170724_075426_create_city cannot be reverted.\n";

        return false;
    }
    */
}
