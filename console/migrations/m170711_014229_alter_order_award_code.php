<?php

use yii\db\Migration;
use yii\db\Schema;

class m170711_014229_alter_order_award_code extends Migration
{
    public function safeUp()
    {
        $this->addColumn('order_award_code', 'deleted_at', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "删除时间"');
        $this->addColumn('order_award_code', 'seller_id', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "卖家"');
        $this->addColumn('order_award_code', 'buyer_id', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "买家"');
        $this->addColumn('order_award_code', 'product_id', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "宝贝id"');
        $this->addColumn('product', 'award_published_at', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "中奖揭晓时间"');
        $this->addColumn('product', 'random_code', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "随机码B, 例如时时彩的5位中奖码"');
        $this->addColumn('product', 'order_award_id', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "中奖id"');
        $this->dropColumn('order_award_code', 'is_win');
        $this->dropColumn('order_award_code', 'user_id');
    }

    public function safeDown()
    {
        echo "m170711_014229_alter_order_award_code cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170711_014229_alter_order_award_code cannot be reverted.\n";

        return false;
    }
    */
}
