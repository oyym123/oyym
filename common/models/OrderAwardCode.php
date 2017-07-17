<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_award_code}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $order_product_id
 * @property integer $buyer_id
 * @property integer $seller_id
 * @property integer $product_id
 * @property string $code
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class OrderAwardCode extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_award_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_product_id', 'created_at', 'updated_at', 'buyer_id', 'seller_id'], 'integer'],
            [['code'], 'string', 'max' => 20],
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

    /** 获取单个用户参与的次数 */
    public function getJoinTimes($userId)
    {
        return self::find()->where(['buyer_id' => $userId])->count();
    }


    /** 获取所有参加的人数 */
    public static function getJoinCount($id)
    {
        return self::find()->where(['product_id' => $id])->count();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', '订单id'),
            'order_product_id' => Yii::t('app', '订单商品表主键'),
            'code' => Yii::t('app', '摇奖编码'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'deleted_at' => Yii::t('app', '删除时间'),
            'seller_id' => Yii::t('app', '卖家'),
            'buyer_id' => Yii::t('app', '买家'),
        ];
    }
}
