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

    /** 设置支付方式 */
    public function setPayType($pay_type)
    {
        $type = array_search($pay_type, BaseData::$payType);
        $this->pay_type = $type ?: '';

        return $this;
    }

    /** 设置支付方式 */
    public function changePayType($pay_type)
    {
        $type = array_search($pay_type, BaseData::$payType);
    }

    /** 获取支付方式 */
    public function getPayType()
    {
        $x = ArrayHelper::getValue(BaseData::$payType, $this->pay_type);
        return ($x == '银行转账') ? '银行柜台转账' : $x;
    }


    /** 获取支付参数 */
    public function getPayParams()
    {
        switch ($this->pay_type) {
            case 1 :  // 支付宝
                return $this->_alipay();
                break;

            case 2: // 微信
                return $this->_weixinPay();
                break;

            default: // 银行行汇款
                return $this->_bankCards();
                break;

            case 4: // 银联
                return $this->_yinlianPay();
                break;
        }
    }

    /** 获取支付宝支付参数 */
    public function _alipay()
    {
        require_once(Yii::getAlias('@app') . "/sdk/alipay/alipay.config.php");
        require_once(Yii::getAlias('@app') . "/sdk/alipay/lib/alipay_core.function.php");
        require_once(Yii::getAlias('@app') . "/sdk/alipay/lib/alipay_rsa.function.php");

        $productTitle = $this->product ? ArrayHelper::getValue($this->product, "0.title") : '';

        $apiParams = [
            'service' => 'mobile.securitypay.pay',
            'out_trade_no' => $this->sn,
            '_input_charset' => 'utf-8',
            'total_fee' => $this->pay_amount,
            'subject' => $productTitle,
            'body' => $productTitle,
            'partner' => '2088801047131045',
            'notify_url' => Url::to('/alipayCallback.php', true),
            'payment_type' => 1,
            'goods_type' => 0, // 0 虚拟 1 实物
            'seller_id' => '1@diyixue.com',
        ];
//        Helper::writeLog($apiParams);
        $apiParams = $apiParams2 = argSort($apiParams);

        foreach ($apiParams as $key => $val) {
            $apiParams[$key] = '"' . $val . '"';
        }

        $apiParams = createLinkstring($apiParams);

        $signData = rsaSign($apiParams, Yii::getAlias('@app') . "/sdk/alipay/key/rsa_private_key.pem");

        return ['alipay' => $apiParams . '&sign_type="RSA"&sign="' . urlencode($signData) . '"'];
    }

    /** 获取微信支付参数 */
    public function _weixinPay()
    {
        $productTitle = $this->product ? ArrayHelper::getValue($this->product, "0.title") : '';

        if ($this->pay) {
            $signParams = [
                'appid' => Yii::$app->params['wx_app_id'],
                'partnerid' => Yii::$app->params['wx_mch_id'],
                'prepayid' => $this->pay->out_trade_no,
                'noncestr' => md5($this->sn . time() . mt_rand(1, 9999999)),
                'timestamp' => (string)time(),
                'package' => "Sign=WXPay",
            ];

            // 如果已经有微信支付日志, 直接返回
            return [
                "wx_appid" => (string)Yii::$app->params['wx_app_id'],
                "wx_sign" => Weixin::getSign($signParams),
                "wx_timestamp" => (string)time(),
                "wx_partner_id" => Yii::$app->params['wx_mch_id'],
                "wx_package" => "Sign=WXPay",
                "wx_nonce_str" => $signParams['noncestr'],
                "wx_prepay_id" => $signParams['prepayid'],
            ];
        }

        $apiParams = [
            'appid' => Yii::$app->params['wx_app_id'],
            'mch_id' => Yii::$app->params['wx_mch_id'],
            'nonce_str' => md5(mt_rand(1, 999999999) . $this->sn),
            'body' => $productTitle,
            'out_trade_no' => $this->sn,
            'total_fee' => $this->pay_amount * 100,
            'spbill_create_ip' => Helper::getIP(),
            'notify_url' => Url::to('/weixinpayCallback.php', true),
            'trade_type' => 'APP',
        ];

        $apiParams['sign'] = Weixin::getSign($apiParams);

        $xml = '<xml>';
        foreach ($apiParams as $key => $val) {
            $xml .= "<$key>$val</$key>";
        }
        $xml .= '</xml>';

        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

        $response = Helper::post($url, $xml);

        list($data, $ok) = Weixin::checkSign($response);

        if ($ok == false) {
            throw new Exception('签名验证失败', -1);
        }

        $xml = simplexml_load_string($response);

        if (empty($data['return_code'])) {
            throw new Exception('创建微信预支付订单失败');
        }

        if ($data['return_code'] == 'FAIL') {
            throw new Exception(empty($data['return_msg']) ? '创建微信预支付订单失败' : $data['return_msg']);
        }

        if ($data['result_code'] == 'FAIL') {
            throw new Exception(empty($data['err_code_des']) ? '创建微信预支付订单失败' : $data['err_code_des']);
        }

        if ($data['return_code'] == 'SUCCESS') {
            if (empty($data['prepay_id'])) {
                throw new Exception((empty($data['return_msg']) ? '创建微信预支付订单失败' : $data['return_msg']));
            }

            /** 保存微信支付日志 */
            $pay = new OrderPay();
            $pay->sn = $this->sn;
            $pay->order_id = $this->id;
            $pay->pay_type = $this->pay_type;
            $pay->out_trade_no = $data['prepay_id'];
            $pay->out_trade_status = '等待付款';
            $pay->log = $response;
            $pay->status = '1';

            if (!$pay->save()) {
                print_r($pay->getErrors());
                throw new Exception('创建订单支付日志失败');
            }

            $sign = Weixin::getSign([
                'appid' => Yii::$app->params['wx_app_id'],
                'partnerid' => Yii::$app->params['wx_mch_id'],
                'partnerid' => $data['mch_id'],
                'prepayid' => $data['prepay_id'],
                'noncestr' => $data['nonce_str'],
                'timestamp' => (string)time(),
                'package' => "Sign=WXPay",
            ]);

            return [
                "wx_appid" => (string)Yii::$app->params['wx_app_id'],
                "wx_sign" => $sign,
                "wx_timestamp" => (string)time(),
                "wx_partner_id" => $data['mch_id'],
                "wx_package" => "Sign=WXPay",
                "wx_nonce_str" => $data['nonce_str'],
                "wx_prepay_id" => $data['prepay_id'],
            ];
        }

        throw new Exception('微信支付暂时不可用');

//        return [
//            "wx_sign" => "d7fe3d29cda19ddf9924e005ee37278a6f3af085",
//            "wx_timestamp" => "1462421594",
//            "wx_partner_id" => "1219130301",
//            "wx_package" => "Sign=WXPay",
//            "wx_nonce_str" => "f4beeba0dd6c190c6ac02f85e7b3691c",
//            "wx_prepay_id" => "820103800016050513bed566589137c9",
//        ];
    }


    /** 获取银联支付参数 */
    public function _yinlianPay()
    {

        header('Content-Type: text/html; charset=utf-8');
        require_once(Yii::getAlias('@app') . "/sdk/yinlian/log.class.php");
        require_once(Yii::getAlias('@app') . "/sdk/yinlian/cert_util.php");
        require_once(Yii::getAlias('@app') . "/sdk/yinlian/common.php");
        require_once(Yii::getAlias('@app') . "/sdk/yinlian/SDKConfig.php");
        $params = array(
            //以下信息非特殊情况不需要改动
            'version' => \SDKConfig::getSDKConfig()->version,                 //版本号
            'encoding' => 'utf-8',                  //编码方式
            'txnType' => '01',                      //交易类型
            'txnSubType' => '01',                  //交易子类
            'bizType' => '000201',                  //业务类型
            'frontUrl' => \SDKConfig::getSDKConfig()->frontUrl,  //前台通知地址
            'backUrl' => \SDKConfig::getSDKConfig()->backUrl,      //后台通知地址
            'signMethod' => \SDKConfig::getSDKConfig()->signMethod,                  //签名方法
            'channelType' => '08',                  //渠道类型，07-PC，08-手机
            'accessType' => '0',                  //接入类型
            'currencyCode' => '156',              //交易币种，境内商户固定156

            'orderId' => $this->sn,  //订单号，演示用
            'merId' => '104100582991374', //商户代码，演示用
            //'merId' => '700000000000001', //商户测试代码，演示用
            'txnTime' => date('YmdHis'),  //订单发送时间，演示用
            'txnAmt' => $this->pay_amount * 100,
            //'txnAmt' => number_format($this->pay_amount) * 100,
            // 请求方保留域，
            // 透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据。
            // 出现部分特殊字符时可能影响解析，请按下面建议的方式填写：
            // 1. 如果能确定内容不会出现&={}[]"'等符号时，可以直接填写数据，建议的方法如下。
            //    'reqReserved' =>'透传信息1|透传信息2|透传信息3',
            // 2. 内容可能出现&={}[]"'符号时：
            // 1) 如果需要对账文件里能显示，可将字符替换成全角＆＝｛｝【】“‘字符（自己写代码，此处不演示）；
            // 2) 如果对账文件没有显示要求，可做一下base64（如下）。
            //    注意控制数据长度，实际传输的数据长度不能超过1024位。
            //    查询、通知等接口解析时使用base64_decode解base64后再对数据做后续解析。
            //    'reqReserved' => base64_encode('任意格式的信息都可以'),

            //TODO 其他特殊用法请查看 pages/api_05_app/special_use_purchase.php
        );


        \AcpService::sign($params); // 签名
        $url = \SDKConfig::getSDKConfig()->appTransUrl;

        $result_arr = \AcpService::post($params, $url);
        if (count($result_arr) <= 0) { //没收到200应答的情况
            //  self::printResult($url, $params, "");
            return [];
        }

        //  self::printResult($url, $params, $result_arr); //页面打印请求应答数据

        if (!\AcpService::validate($result_arr)) {
            // echo "应答报文验签失败<br>\n";
            return [];
        }

        // echo "应答报文验签成功<br>\n";
        if ($result_arr["respCode"] == "00") {
            //成功
            //TODO
            /** 保存微信支付日志 */
            $pay = new OrderPay();
            $pay->sn = $this->sn;
            $pay->order_id = $this->id;
            $pay->pay_type = $this->pay_type;
            $pay->out_trade_no = $result_arr["tn"];
            $pay->out_trade_status = '等待付款';
            $pay->log = '';
            $pay->status = '1';

            if (!$pay->save()) {
                print_r($pay->getErrors());
                throw new Exception('创建订单支付日志失败');
            }

            return [
                "yl_tn" => $result_arr["tn"]
            ];
        } else {
            //其他应答码做以失败处理
            //TODO
            //echo "失败：" . $result_arr["respMsg"] . "。<br>\n";
            return [];
        }
    }

    /** 获取支付方式 */
    public function getPayTypes($payIds)
    {
        $payIds = @json_decode($payIds, true);

        $return = [];

        if (is_array($payIds)) {

            if (in_array('微信支付', $payIds)) {
                $return[] = [
                    'show' => 1,
                    'detail' => '推荐开通微信支付的用户',
                    'type' => BaseData::$payType[2],
                    'img' => Url::to('/images/pay_weixin.png', 1)
                ];
            }

            if (in_array('支付宝支付', $payIds)) {
                $return[] = [
                    'show' => 1,
                    'detail' => '推荐有支付宝的用户使用',
                    'type' => BaseData::$payType[1],
                    'img' => Yii::$app->params['qiniu_url_images'] . 'pay_alipay1.png'
                ];
            }

            if (in_array('银联支付', $payIds)) {
                $return[] = [
                    'show' => 1,
                    'detail' => '推荐有银行卡的用户使用',
                    'type' => BaseData::$payType[4],
                    'img' => Yii::$app->params['qiniu_url_images'] . 'pay_yinlian1.png'
                ];
            }

            if (in_array('银行转账', $payIds)) {
                $return[] = [
                    'show' => 1,
                    'detail' => '推荐没有第三方支付的用户',
                    'type' => BaseData::$payType[3],
                    'img' => Yii::$app->params['qiniu_url_images'] . 'pay_huikuan1.png'
                ];
            }

            if (in_array('银行柜台转账', $payIds)) {
                $return[] = [
                    'show' => 1,
                    'detail' => '推荐没有第三方支付的用户',
                    'type' => '银行柜台转账',
                    'img' => Yii::$app->params['qiniu_url_images'] . 'pay_huikuan1.png'
                ];
            }

        }

        return $return;
    }


}