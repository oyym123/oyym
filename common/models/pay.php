<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/6/23
 * Time: 17:03
 */

namespace common\models;

use yii\base\Model;
use app\helpers\Helper;
use app\helpers\Weixin;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


class pay extends Base
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'out_trade_no', 'out_trade_status', 'log', 'order_id', 'sn'], 'default', 'value' => ''],
            [['paid_at'], 'default', 'value' => 0],
            [['order_id', 'sn', 'pay_type', 'status'], 'required'],
            [['order_id', 'pay_type', 'created_at', 'updated_at'], 'integer'],
            [['sn', 'out_trade_no', 'out_trade_status'], 'string', 'max' => 45],
            [['out_trade_no', 'out_trade_status'], 'string', 'max' => 45],
            [['log'], 'string', 'max' => 10000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'sn' => Yii::t('app', '订单号'),
            'pay_type' => Yii::t('app', '支付方式'),
            'status' => Yii::t('app', '记录支付接口相关的状态，并非订单支付状态'),
            'out_trade_no' => Yii::t('app', '支付宝交易号或微信交易号'),
            'out_trade_status' => Yii::t('app', '支付宝或微信交易状态'),
            'log' => Yii::t('app', '支付日志'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'bank_pay_img' => Yii::t('app', '银行柜台转账凭证'),
            'bank_pay_time' => Yii::t('app', '汇款时间'),
            'bank_card_id' => Yii::t('app', '往哪个账号转账'),
            'bank_user_name' => Yii::t('app', '汇款人姓名'),
            'user_bank_num' => Yii::t('app', '汇款人卡号'),
        ];
    }

    public function getOrder() {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

}