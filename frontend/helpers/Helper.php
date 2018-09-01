<?php
namespace app\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseArrayHelper;

class Helper extends BaseArrayHelper
{
    public static function post($url, $post_data)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据

        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        $output = curl_exec($ch);

        curl_close($ch);
        //打印获得的数据

        return $output;
    }

    /**
     * 发送短信验证码
     * Desc:
     * User: lixinxin <lixinxinlgm@163.com>
     * Date: 2016-03-07
     *
     * $params = [
     *  'mobile' => '手机号'
     *  'code' => '验证码'
     *  'product' => app名字
     *  'type' => "登录验证"
     *  'template' => '阿里大鱼的模板'
     * ]
     *
     */
    public static function sendSms($params)
    {
        $url = 'https://' . $_SERVER["HTTP_HOST"] . '/sdk/taobao/api.php';

        $params['time'] = time();
        ksort($params);
        $params['sign'] = md5(implode(',', $params) . Yii::$app->params['alidayu.secretKey']);

        return self::post($url, $params);

        /*
//        echo Yii::$app->basePath . "/../sdk/taobao/TopSdk.php";exit;
        include "/Users/L/51zs/frontend/sdk/taobao/TopSdk.php";
//        include Yii::$app->basePath . "/../sdk/taobao/top/TopClient.php";

//        echo Yii::$app->basePath . "/../sdk/taobao/top/TopClient.php";exit;
        date_default_timezone_set('Asia/Shanghai');

        $c = new TopClient;
        $c->appkey = Yii::$app->params['alidayu.appkey'];
        $c->secretKey = Yii::$app->params['alidayu.secretKey'];
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("1");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($params['type']);
        $req->setSmsParam(json_encode(['code' => $params['code'], 'product' => $params['product']])); // '{"code":"","product":" 51招生 "}'
        $req->setRecNum("18606615070");
        $req->setSmsTemplateCode($params['template']);
        $resp = $c->execute($req);

        return json_decode($resp);
        */
    }


    /** 切割字符串 */
    public static function mbStrSplit($string, $len = 1)
    {
        $start = 0;
        $strlen = mb_strlen($string);
        $array = [];
        while ($strlen) {
            $array[] = mb_substr($string, $start, $len, "utf8");
            $string = mb_substr($string, $len, $strlen, "utf8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }

    /** 判断是否是手机 */
    public static function isMobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$|^17[\d]{9}$#', $mobile) ? true : false;
    }

    /** 写入测试数据 */
    public static function writeLog($data)
    {
        file_put_contents('/tmp/request.log', date('Y-m-d-H:i:s') . var_export($data, 1) . "\n", FILE_APPEND);
    }

    public static function errors()
    {
        return [
            '-10' => '取消订单失败',
            '-100' => '需要登录',
        ];
    }

    /** 隐藏手机号中间的4位 */
    public static function formatMobile($mobile)
    {
        if ($mobile) {
            return substr_replace($mobile, '****', 3, 4);
        }
    }

    /** 取header头信息 */
    public static function headerInfo($url, $key)
    {
        if ($url) {
            $header = get_headers($url, true);
            return ArrayHelper::getValue($header, $key, '');
        }
    }


    /** 获取ip */
    public static function getIP()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function breakString($str, $num)
    {
        preg_match_all("/./u", $str, $arr);//将所有字符转成单个数组

        //print_r($arr);

        $strstr = '';
        $width = 0;
        $arr = $arr[0];
        foreach ($arr as $key => $string) {
            $strlen = strlen($string);//计算当前字符的长度，一个字母的长度为1，一个汉字的长度为3
            //echo $strlen;

            if ($strlen == 3) {

                $width += 1;

            } else {

                $width += 0.5;

            }

            $strstr .= $string;

            //计算当前字符的下一个
            if (array_key_exists($key + 1, $arr)) {
                $_strlen = strlen($arr[$key + 1]);
                if ($_strlen == 3) {
                    $_width = 1;
                } else {
                    $_width = 0.5;
                }
                if ($width + $_width > $num) {
                    $width = 0;
                    $strstr .= "\n";
                }
            }

        }
        return $strstr;
    }


    public static function getInfo($url = '')
    {

        //初始化
        $curlobj = curl_init();
        //设置访问的url
        curl_setopt($curlobj, CURLOPT_URL, $url);
        //执行后不直接打印出
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);
        //  curl_setopt($curlobj, CURLOPT_HEADER, 0);
        //设置https 支持
        // date_default_timezone_get('PRC');   //使用cookies时，必须先设置时区
        curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, 0);  //终止从服务端验证
        curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1);
        $output = curl_exec($curlobj);  //执行获取内容
        curl_close($curlobj);          //关闭curl
        return $output;
    }
}