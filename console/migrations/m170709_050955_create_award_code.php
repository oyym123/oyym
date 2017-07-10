<?php

use yii\db\Migration;
use yii\db\Schema;

class m170709_050955_create_award_code extends Migration
{
    public function safeUp()
    {
        $this->createTable('order_award_code', [
            'id' => Schema::TYPE_PK,
            'order_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "订单id"',
            'order_product_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "订单商品表主键"',
            'code' => Schema::TYPE_STRING . '(20) NOT NULL DEFAULT 0 COMMENT "摇奖编码"',
            'is_win' => Schema::TYPE_INTEGER . '(1) NOT NULL DEFAULT 0 COMMENT "是否中奖"',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "买家id"',
            'created_at' => Schema:: TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "创建时间"',
            'updated_at' => Schema:: TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "更新时间"'
        ]);
    }

    public function safeDown()
    {
        echo "m170709_050955_create_award_code cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170709_050955_create_award_code cannot be reverted.\n";

        return false;
    }
    */
}
