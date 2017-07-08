<?php

use yii\db\Migration;

class m170707_014514_create_tag_coupon extends Migration
{
    public function safeUp()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `tag_coupon` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tid` int(11) NOT NULL DEFAULT '0' COMMENT '产品标签id',
              `coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券id',
              `created_at` varchar(45) NOT NULL,
              `updated_at` varchar(45) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB COMMENT='产品标签和优惠券关系表';
        ";
        $count = Yii::$app->db->createCommand($sql)->query();
    }

    public function safeDown()
    {
        echo "m170707_014514_create_tag_coupon cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170707_014514_create_tag_coupon cannot be reverted.\n";

        return false;
    }
    */
}
