<?php

use yii\db\Migration;
use yii\db\Schema;

class m170621_071921_create_user_address extends Migration
{
    public function safeUp()
    {
        $this->createTable('user_address', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL  COMMENT "用户地址"',
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT "用户id"',
            'lng' => Schema::TYPE_STRING . '(25) NOT NULL COMMENT "经度"',
            'lat' => Schema::TYPE_STRING . '(25) NOT NULL COMMENT "纬度"',
            'province_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "省级id"',
            'city_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "市级id"',
            'area_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "地区id"',
            'default_address' => Schema::TYPE_INTEGER . '(3) NOT NULL DEFAULT 0 COMMENT "是否是默认地址"',
            'status' => Schema::TYPE_INTEGER . '(3) NOT NULL DEFAULT 0 COMMENT "地址状态"',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "创建时间"',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL  COMMENT "更新时间"',
        ]);
    }

    public function safeDown()
    {
        echo "m170621_071921_create_user_address cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170621_071921_create_user_address cannot be reverted.\n";

        return false;
    }
    */
}
