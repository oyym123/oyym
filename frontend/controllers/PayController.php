<?php

/**
 * @link http://www.51zzzs.cn/
 * @copyright 2016 中国自主招生网
 * @author lixinxin@zgzzzs.com
 */
namespace frontend\controllers;

use app\helpers\Helper;
use app\helpers\Weixin;
use common\models\Order;
use common\models\Pay;
use common\models\UserBought;
use frontend\components\WebController;
use Yii;
use yii\base\Exception;

class PayController extends WebController
{
    /**
     * 支付宝支付异步通知接口
     * 接口说明地址: https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.mIBVew&treeId=59&articleId=103666&docType=1
     */

    /** 支付宝支付回传 */
    public function actionAlipayCallback()
    {
        $alipay_config = [];
        require_once(Yii::getAlias('@app') . "/sdk/alipay/alipay.config.php");
        $alipay_config['private_key_path'] = Yii::getAlias('@app') . "/sdk/alipay/key/rsa_private_key.pem";
        $alipay_config['ali_public_key_path'] = Yii::getAlias('@app') . "/sdk/alipay/key/alipay_public_key.pem";

        require_once(Yii::getAlias('@app') . "/sdk/alipay/lib/alipay_notify.class.php");

        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        if ($verify_result) {//验证成功

            if (Yii::$app->request->post('trade_status') != 'TRADE_FINISHED') {
                echo 'success';
                exit;
            }

            $order = Order::findOne(['sn' => Yii::$app->request->post('out_trade_no')]);

            if (Yii::$app->request->post('seller_id') != $alipay_config['partner']) {
                Helper::writeLog('支付宝用户号错误');
                exit;
            }

            if (empty($order)) {
                Helper::writeLog('订单不存在');
                exit;
            }

            if (!$order->isCanPay()) {
                Helper::writeLog('订单不允许支付');
            }

            if ($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");

            } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }

            if (empty($order->pay)) {
                $order->pay_type = 1;
                $pay = new Pay();
                $pay->sn = $order->sn;
                $pay->order_id = $order->id;
                $pay->pay_type = $order->pay_type;
                $pay->out_trade_no = Yii::$app->request->post('trade_no');
                $pay->out_trade_status = Yii::$app->request->post('trade_status');
                $pay->log = '无';
                $pay->status = '1';
                $pay->paid_at = time();

                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if (!$pay->save()) {
                        throw new Exception('创建订单支付日志失败');
                    }

                    $order->status = Order::STATUS_PAYED;

                    if (!$order->save()) {
                        throw new Exception('修改订单支付状态失败');
                    }

                    if (!empty($order->product)) {
                        foreach ($order->product as $item) {
                            $userBouht = new UserBought();
                            $userBouht->pid = $item->pid; // 商品id
                            $userBouht->user_id = $order->user_id;
                            if (!$userBouht->save()) {
                                throw new Exception('保存用户购买记录失败');
                            }
                        }
                    }

                    $transaction->commit();
                    echo "success";        //请不要修改或删除
                    exit;
                } catch (Exception $e) {
                    Helper::writeLog($_REQUEST);
                    Helper::writeLog($e->getMessage());
                    $transaction->rollBack();
                    echo "fail";
                }
            }
        } else {
            //验证失败
            echo "fail";
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }

    }

    /** 微信支付回传 */
    public function actionWeixinpayCallback()
    {

//        $xml = '<xml><appid><![CDATA[wx5aae19130d08e94a]]></appid>
//<bank_type><![CDATA[CFT]]></bank_type>
//<cash_fee><![CDATA[2]]></cash_fee>
//<fee_type><![CDATA[CNY]]></fee_type>
//<is_subscribe><![CDATA[N]]></is_subscribe>
//<mch_id><![CDATA[1334277101]]></mch_id>
//<nonce_str><![CDATA[45f64d3bf283a3b1136dd1a5f1d59ae8]]></nonce_str>
//<openid><![CDATA[o7-kas-b3O1_VE2R4UNc5uZRfuH0]]></openid>
//<out_trade_no><![CDATA[2016061549529797]]></out_trade_no>
//<result_code><![CDATA[SUCCESS]]></result_code>
//<return_code><![CDATA[SUCCESS]]></return_code>
//<sign><![CDATA[C1C0505E8C2DD4D0B4175AD0C9FDC83D]]></sign>
//<time_end><![CDATA[20160615144708]]></time_end>
//<total_fee>2</total_fee>
//<trade_type><![CDATA[APP]]></trade_type>
//<transaction_id><![CDATA[4001082001201606157300944188]]></transaction_id>
//</xml>';

        $xml = file_get_contents('php://input');
        list($data, $ok) = Weixin::checkSign($xml);

        if (!$ok) {
            echo Weixin::xml([
                'return_code' => 'FAIL',
                'return_msg' => '签名失败',
            ]);
            exit;
        }

//        $data['out_trade_no'] = '2016061599575552';
        $order = Order::findOne(['sn' => $data['out_trade_no']]);

        if (empty($order) || empty($order->pay)) {
            Helper::writeLog('订单不存在');
            echo Weixin::xml([
                'return_code' => 'FAIL',
                'return_msg' => '订单不存在',
            ]);
            exit;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $this->_checkWeixinOrder($order, $data);

            $order->status = Order::STATUS_PAYED;
            $order->pay_type = 2;

            if (!$order->save()) {
                throw new Exception(current($order->getFirstErrors()));
            }

            $order->pay->weixin_pay_xml = $xml;
            $order->pay->pay_type = $order->pay_type;
            $order->pay->out_trade_status = '已付款';
            $order->pay->paid_at = time();

            if (!$order->pay->save()) {
                throw new Exception(current($order->pay->getFirstErrors()));
            }

            if (!empty($order->product)) {
                foreach ($order->product as $item) {
                    $userBouht = new UserBought();
                    $userBouht->pid = $item->pid; // 商品id
                    $userBouht->user_id = $order->user_id;
                    if (!$userBouht->save()) {
                        throw new Exception('保存用户购买记录失败');
                    }
                }
            }

            $transaction->commit();

            echo Weixin::xml([
                'return_code' => 'SUCCESS',
                'return_msg' => 'OK',
            ]);
        } catch (Exception $e) {

            $transaction->rollBack();

            Helper::writeLog('微信支付:' . $e->getMessage());

            echo Weixin::xml([
                'return_code' => 'FAIL',
                'return_msg' => $e->getMessage(),
            ]);
        }
    }

    /** 检查微信回调 */
    protected function _checkWeixinOrder($order, $data)
    {
        if ($order->pay_amount * 100 != $data['total_fee']) {
            throw new Exception('支付金额不符');
        }

        if (!$order->isCanPay()) {
            throw new Exception('订单不允许支付');
        }

        if ($data['mch_id'] != Yii::$app->params['wx_mch_id']) {
            throw new Exception('商户号不符');
        }
    }

    /** 银联支付回传 */
    public function actionYinlianpayCallback()
    {
        require_once(Yii::getAlias('@app') . "/sdk/yinlian/acp_service.php");
        require_once(Yii::getAlias('@app') . "/sdk/yinlian/log.class.php");
        require_once(Yii::getAlias('@app') . "/sdk/yinlian/cert_util.php");
        require_once(Yii::getAlias('@app') . "/sdk/yinlian/common.php");
        require_once(Yii::getAlias('@app') . "/sdk/yinlian/SDKConfig.php");

        echo '<table width="800px" border="1" align="center">';
        echo '<tr>';
        echo '	<th colspan="2" align="center">银联在线交易测试-交易结果</th>';
        echo '</tr>';
        foreach ($_POST as $key => $val) {

            Helper::writeLog(isset($mpi_arr[$key]) ? $mpi_arr[$key] : $key . '------->' . $val);
            echo '   <tr>';
            echo '<td width="30%">';
            echo isset($mpi_arr[$key]) ? $mpi_arr[$key] : $key;
            echo '</td>';
            echo '<td>';
            echo $val;
            echo '   </td>';
            echo '</tr>';
        }
        echo '<tr>';
        echo "<td width='30%'>验证签名</td>";
        echo ' <td>';
        if (isset ($_POST ['signature'])) {

            echo \AcpService::validate($_POST) ? '验签成功' : '验签失败';
            $orderId = $_POST ['orderId']; //其他字段也可用类似方式获取
            $respCode = $_POST ['respCode']; //判断respCode=00或A6即可认为交易成功
            Helper::writeLog($respCode == 00 ? '银联交易成功' : '银联交易失败');
        } else {
            exit;
        }
        echo '</td>';
        echo '</tr>';
        echo '</table>';

        $order = Order::findOne(['sn' => $_POST['orderId']]);

        if (empty($order)) {
            Helper::writeLog('订单不存在');
            exit;
        }

        if (!$order->isCanPay()) {
            Helper::writeLog('订单不允许支付');
        }

        if ($order->pay) {
            $pay = Pay::findOne(['order_id' => $order->id]);
            $pay->sn = $order->sn;
            $pay->order_id = $order->id;
            $pay->pay_type = $order->pay_type;
            $pay->out_trade_no = Yii::$app->request->post('queryId');
            $pay->out_trade_status = '已付款';
            $pay->log = '无';
            $pay->status = '1';
            $pay->paid_at = time();
            $transaction = Yii::$app->db->beginTransaction();
            Helper::writeLog('已进入到修改订单状态中');

            try {
                if (!$pay->save()) {
                    throw new Exception('创建订单支付日志失败');
                }

                $order->status = Order::STATUS_PAYED;

                if (!$order->save()) {
                    throw new Exception('修改订单支付状态失败');
                }

                if (!empty($order->product)) {
                    foreach ($order->product as $item) {
                        $userBouht = new UserBought();
                        $userBouht->pid = $item->pid; // 商品id
                        $userBouht->user_id = $order->user_id;
                        if (!$userBouht->save()) {
                            throw new Exception('保存用户购买记录失败');
                        }
                    }
                }
                $transaction->commit();
                echo "success";        //请不要修改或删除
                //exit;
            } catch (Exception $e) {
                Helper::writeLog($_REQUEST);
                Helper::writeLog($e->getMessage());
                $transaction->rollBack();
                echo "fail";
            }

        } else {
            //验证失败
            echo "fail";
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }
}


