<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_product}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $pid
 * @property integer $buyer_id
 * @property integer $seller_id
 * @property string $title
 * @property integer $count
 * @property string $price
 * @property string $discount_price
 * @property integer $coupon_id
 * @property integer $buy_type
 * @property integer $model_type
 * @property integer $created_at
 * @property integer $updated_at
 */
class OrderProduct extends Base
{

    const A_PRICE = 1; // 一口价
    const UNIT_PRICE = 2; // 单价

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'pid', 'buyer_id', 'seller_id', 'count', 'coupon_id', 'buy_type', 'model_type', 'created_at', 'updated_at'], 'integer'],
            [['price', 'discount_price', 'random_code'], 'number'],
            [['title'], 'string', 'max' => 200],
        ];
    }

    /** 获取卖家用户信息 */
    public function getSeller()
    {
        return $this->hasOne(User::className(), ['id' => 'seller_id']);
    }

    /** 获取买家用户信息 */
    public function getBuyer()
    {
        return $this->hasOne(User::className(), ['id' => 'buyer_id']);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', '订单号'),
            'pid' => Yii::t('app', '宝贝id'),
            'buyer_id' => Yii::t('app', '买家id'),
            'seller_id' => Yii::t('app', '卖家id'),
            'random_code' => Yii::t('app', '随机码'),
            'title' => Yii::t('app', '宝贝标题'),
            'count' => Yii::t('app', '购买数量'),
            'price' => Yii::t('app', '购买价格'),
            'discount_price' => Yii::t('app', '折扣价格'),
            'coupon_id' => Yii::t('app', '优惠券id'),
            'buy_type' => Yii::t('app', '购买类型:1=一口价2=单价'),
            'model_type' => Yii::t('app', 'json序列化其他信息'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }

}
