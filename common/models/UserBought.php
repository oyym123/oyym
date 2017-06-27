<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_bought".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $user_id
 * @property string $ip
 * @property integer $address_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserBought extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_bought';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'user_id', 'created_at', 'updated_at'], 'required'],
            [['product_id', 'user_id', 'address_id', 'created_at', 'updated_at'], 'integer'],
            [['ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => '产品ID',
            'user_id' => '用户ID',
            'ip' => 'Ip',
            'address_id' => '地址',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
