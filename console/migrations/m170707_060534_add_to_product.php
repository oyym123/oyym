<?php

use yii\db\Migration;
use yii\db\Schema;

class m170707_060534_add_to_product extends Migration
{
    public function safeUp()
    {
        $this->addColumn('product', 'a_price', Schema::TYPE_DECIMAL . '(10,2) COMMENT "一口价"');
        $this->addColumn('product', 'unit_price', Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT "单价"');
        $this->addColumn('product', 'model', Schema::TYPE_INTEGER . '(3) NOT NULL COMMENT "模式"');
        $this->addColumn('product', 'detail_address', Schema::TYPE_STRING . '(255) NOT NULL COMMENT "详细地址"');
        $this->addColumn('product', 'lat', Schema::TYPE_STRING . '(255) COMMENT "纬度"');
        $this->addColumn('product', 'lng', Schema::TYPE_STRING . '(255) COMMENT "经度"');
        $this->addColumn('product', 'start_time', Schema::TYPE_INTEGER . '(11) COMMENT "发布时间"');
        $this->addColumn('product', 'end_time', Schema::TYPE_INTEGER . '(11) COMMENT "结束时间"');
        $this->addColumn('product', 'created_by', Schema::TYPE_INTEGER . '(11) COMMENT "创建者"');
        $this->addColumn('product', 'total', Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "总数量"');
    }

    public function safeDown()
    {
        echo "m170707_060534_add_to_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170707_060534_add_to_product cannot be reverted.\n";

        return false;
    }
    */
}
