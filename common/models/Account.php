<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%account}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $pay_id
 * @property integer $type
 * @property string $amount
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Account extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'pay_id', 'type', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', '订单id'),
            'pay_id' => Yii::t('app', '订单id'),
            'type' => Yii::t('app', '1=支付2=收入3=退款'),
            'amount' => Yii::t('app', '金额'),
            'user_id' => Yii::t('app', '用户id'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }
}
