<?php

use yii\db\Migration;
use yii\db\Schema;

class m170621_084244_create_product_type extends Migration
{
    public function safeUp()
    {
        $this->createTable('product_type', [
            'id' => Schema::TYPE_PK,
            'pid' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "父类ID"',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL COMMENT "产品类型名称"',
            'level' => Schema::TYPE_INTEGER . '(3) NOT NULL DEFAULT 0 COMMENT "用户收益"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170621_084244_create_product_type cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_084244_create_product_type cannot be reverted.\n";

        return false;
    }
    */
}
