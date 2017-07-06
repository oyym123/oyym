<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_type".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $name
 * @property integer $level
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProductType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'name', 'created_at', 'updated_at'], 'required'],
            [['pid', 'level', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '父类ID',
            'name' => '产品类型名称',
            'level' => '等级',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
