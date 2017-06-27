<?php
namespace app\helpers;

use Yii;
use yii\helpers\BaseArrayHelper;

class Weixin extends BaseArrayHelper
{
    /** 微信支付签名 */
    public static function getSign($data)
    {
        ksort($data);

        $s = '';
        foreach ($data as $key => $val) {
            if ($val) {
                $s .= "$key=$val&";
            }
        }
        $s .= 'key=' . Yii::$app->params['wx_pay_secret'];

        return strtoupper(md5($s));
    }

    /** 检查微信返回 */
    public static function checkSign($xml)
    {
        $xmlOjb = simplexml_load_string($xml);

        if (!is_object($xmlOjb)) {
            return false;
        }

        $data = $params = [];
        foreach ($xmlOjb as $key => $val) {
            $key = (string)$key;
            $val = (string)$val;
            if ($key != 'sign') {
                $params[$key] = $val;
            }
            $data[$key] = $val;
        }

        return [$data, self::getSign($params) == $data['sign']];
    }

    /** 生成xml数据 */
    public static function xml($data) {
        if (!is_array($data) || empty($data)) {
            return '';
        }

        $r = '<xml>';
        foreach ($data as $key => $val) {
            $r .= "<$key><![CDATA[$val]]</$key>\n";
        }
        $r .= '</xml>';

        return $r;
    }
}