<?php

use yii\db\Migration;
use yii\db\Schema;

class m170705_031103_add_to_user_address extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('user_address', 'name');
        $this->addColumn('user_address', 'user_name', Schema::TYPE_STRING . '(255) NOT NULL COMMENT "收货人姓名"');
        $this->addColumn('user_address', 'telephone', Schema::TYPE_STRING . '(18) NOT NULL COMMENT "电话号码"');
        $this->addColumn('user_address', 'postal', Schema::TYPE_STRING . '(12) COMMENT "邮政编码"');
        $this->addColumn('user_address', 'street_id', Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "街道ID"');
        $this->addColumn('user_address', 'detail_address', Schema::TYPE_STRING . '(255) NOT NULL COMMENT "详细地址"');
        $this->addColumn('user_address', 'str_address', Schema::TYPE_STRING . '(255) NOT NULL COMMENT "地址拼接字符串"');
    }

    public function safeDown()
    {
        echo "m170705_031103_add_to_user_address cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170705_031103_add_to_user_address cannot be reverted.\n";

        return false;
    }
    */
}
