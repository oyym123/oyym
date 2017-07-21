<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_coupon".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $user_id
 * @property integer $coupon_id
 * @property string $order_id
 * @property integer $sort
 * @property integer $used_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserCoupon extends Base
{
    public $canUseCoupon = []; // 用户可以使用的优惠券
    const STATUS_UNUSED = 10; // 已激活
    const STATUS_USED = 20; // 已使用

    /** 优惠券状态 */
    public static function usedStatus() {
        return [
            self::STATUS_UNUSED => '已激活',
            self::STATUS_USED => '已使用',
        ];
    }

    /** 优惠券样式 */
    public static function usedStatusColor() {
        return [
            self::STATUS_UNUSED => 'red', // 红色
            self::STATUS_USED => 'gray', // 灰色
        ];
    }

    public function getUsedStatus() {
        return ArrayHelper::getValue(self::usedStatus(), $this->status);
    }

    public function getUsedStatusColor() {
        return ArrayHelper::getValue(self::usedStatusColor(), $this->status);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'user_id', 'coupon_id', 'order_id', 'created_at', 'updated_at'], 'required'],
            [['status', 'user_id', 'coupon_id', 'sort', 'used_at', 'created_at', 'updated_at'], 'integer'],
            [['order_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => '状态',
            'user_id' => '用户ID',
            'coupon_id' => '优惠券ID',
            'order_id' => '订单ID',
            'sort' => '排序',
            'used_at' => '使用区域',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
