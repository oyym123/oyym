<?php

use yii\db\Migration;
use yii\db\Schema;

class m170621_121840_create_comments extends Migration
{
    public function safeUp()
    {
        $this->createTable('comments', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "评论者ID"',
            'type_id' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "评论类型ID"',
            'type' => Schema::TYPE_INTEGER . '(4) NOT NULL  COMMENT "评论类型"',
            'contents' => Schema::TYPE_TEXT . ' COMMENT "评论内容"',
            'star' => Schema::TYPE_INTEGER . '(4) NOT NULL COMMENT "评论星级"',
            'sort' => Schema::TYPE_INTEGER . '(4) NOT NULL COMMENT "评论排序"',
            'child_ids' => Schema::TYPE_STRING . '(1000) COMMENT "评论子类ID"',
            'parent_id' => Schema::TYPE_INTEGER . '(11)  COMMENT "评论父类"',
            'like_count' => Schema::TYPE_INTEGER . '(11)  COMMENT "点赞人数"',
            'comment_count' => Schema::TYPE_INTEGER . '(11)  COMMENT "评论人数"',
            'comment_line_id' => Schema::TYPE_INTEGER . '(11)  COMMENT "评论所属内容的comment表id,不是互相评论的id"',
            'status' => Schema::TYPE_INTEGER . '(4) NOT NULL COMMENT "评论状态"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170621_121840_create_comments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_121840_create_comments cannot be reverted.\n";

        return false;
    }
    */
}
