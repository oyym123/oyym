<?php
use yii\db\Schema;
use yii\db\Migration;

class m170720_093127_create_attention extends Migration
{
    public function safeUp()
    {
        $this->createTable('attention', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "关注者ID"',
            'type_id' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "关注的类型ID"',
            'type' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "关注的类型"',
            'status' => Schema::TYPE_INTEGER . '(4) NOT NULL COMMENT "关注状态"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170720_093127_create_attention cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170720_093127_create_attention cannot be reverted.\n";

        return false;
    }
    */
}
