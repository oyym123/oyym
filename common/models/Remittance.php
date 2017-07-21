<?php

namespace common\models;

use Yii;

/**
 * 汇款记录表
 * This is the model class for table "{{%remittance}}".
 *
 * @property integer $id
 * @property integer $from_account_type
 * @property string $from_account_params
 * @property integer $to_account_type
 * @property string $to_account_params
 * @property integer $type
 * @property integer $status
 * @property string $amount
 * @property integer $order_id
 * @property integer $user_id
 * @property string $out_trade_no
 * @property string $out_trade_status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Remittance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%remittance}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_account_type', 'to_account_type', 'type', 'status', 'order_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['from_account_params', 'to_account_params'], 'required'],
            [['from_account_params', 'to_account_params'], 'string'],
            [['amount'], 'number'],
            [['out_trade_no', 'out_trade_status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'from_account_type' => Yii::t('app', '公司的方式1=通过公司支付宝汇款;2=通过公司微信汇款'),
            'from_account_params' => Yii::t('app', '扣款账户所需要用到的参数serialize格式'),
            'to_account_type' => Yii::t('app', '用户的收款账户类型1=支付宝;2=微信'),
            'to_account_params' => Yii::t('app', '收款账户所需要用到的参数serialize格式'),
            'type' => Yii::t('app', '汇款类型:1=参与宝贝退款;2=卖出宝贝获得收益'),
            'status' => Yii::t('app', '0=等待转账; 1=转账中; 2=转账成功 3=转账失败'),
            'amount' => Yii::t('app', '转账金额'),
            'order_id' => Yii::t('app', '订单id'),
            'user_id' => Yii::t('app', '用户id'),
            'out_trade_no' => Yii::t('app', '返回的外部流水号'),
            'out_trade_status' => Yii::t('app', '支付状态中文显示'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }
}
