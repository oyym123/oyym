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
            [['price', 'discount_price'], 'number'],
            [['title'], 'string', 'max' => 200],
        ];
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

    /** 获取订单信息 */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /** 获取参与次数订单信息 */
    public function getOrderAward()
    {
        return $this->hasMany(OrderAwardCode::className(), ['order_product_id' => 'id']);
    }

    /** 取宝贝 */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'pid']);
    }

    /** 买家-我参与的/买到的宝贝列表 样式布局 */
    public function buyerProductLayout()
    {
        if ($this->buy_type == self::A_PRICE) {
            return '一口价购买';
        }

        $r = '进行中';
        if ($this->product->status == Product::STATUS_IN_PROGRESS) {
            $r = '进行中';
        } elseif ($this->product->status == Product::STATUS_WAIT_PUBLISH) {
            $r = '待揭晓';
        } elseif ($this->product->status == Product::STATUS_PUBLISHED) {
            $r = '已揭晓';
        }

        return $this->product->modelTypeText() . '_买家_' . $r;;
    }
}
