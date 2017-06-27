<?php

use yii\db\Migration;
use yii\db\Schema;

class m170623_072253_create_coupon extends Migration
{
    public function safeUp()
    {
        $this->createTable('coupon', [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_INTEGER . '(5) NOT NULL  COMMENT "优惠券类型"',
            'title' => Schema::TYPE_STRING . '(255) NOT NULL  COMMENT "优惠券名称"',
            'start_time' => Schema::TYPE_INTEGER . '(11) COMMENT "开始时间"',
            'end_time' => Schema::TYPE_INTEGER . '(11) COMMENT "结束时间"',
            'created_by' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT  "创建该优惠券用户ID"',
            'updated_by' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT  "修改该优惠券用户ID"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170623_072253_create_coupon cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170623_072253_create_coupon cannot be reverted.\n";

        return false;
    }
    */
}
