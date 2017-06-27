<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $sn
 * @property integer $pay_type
 * @property string $pay_amount
 * @property string $product_amount
 * @property string $discount_amount
 * @property string $user_name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Order extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'sn', 'pay_type', 'user_name', 'status', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'pay_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pay_amount', 'product_amount', 'discount_amount'], 'number'],
            [['sn'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'sn' => '订单号',
            'pay_type' => '支付类型',
            'pay_amount' => '支付金额',
            'product_amount' => '产品金额',
            'discount_amount' => '优惠后的金额',
            'user_name' => '用户名称',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
