<?php

use yii\db\Migration;
use yii\db\Schema;

class m170621_072638_create_product extends Migration
{
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "用户ID"',
            'type_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "产品类型id"',
            'title' => Schema::TYPE_STRING . '(255) NOT NULL COMMENT "产品标题"',
            'price' => Schema::TYPE_DECIMAL . '(10,2)  NOT NULL DEFAULT 0 COMMENT "产品价格"',
            'original_price' => Schema::TYPE_DECIMAL . '(10,2)  NOT NULL DEFAULT 0 COMMENT "产品原价格"',
            'unit' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "单位数量"',
            'contents' => Schema::TYPE_TEXT . ' COMMENT "产品内容"',
            'watches' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "观看人数"',
            'comments' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "评论人数"',
            'freight' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0 COMMENT "运费"',
            'sort' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "产品排序"',
            'status' => Schema::TYPE_INTEGER . '(3) NOT NULL DEFAULT 0 COMMENT "产品状态"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170621_072638_create_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_072638_create_product cannot be reverted.\n";

        return false;
    }
    */
}
