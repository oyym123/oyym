<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * 数据
 *
 */
trait BaseData
{
    /** ios 版本 */
    public static $iosVersion = [

    ];

    /** andiroid 版本 */
    public static $androidVersion = [

    ];

    /** andiroid 版本 */
    public static $windowsVersion = [

    ];

    /** 手机系统类型 */
    public static $phoneSystem = [
        1 => 'ios',
        2 => 'android',
        3 => 'windows',
    ];

    /** 手机型号model */
    public static $phoneModel = [
        0 => '未知',
        1 => 'iphone4s',
        2 => 'iphone5',
        3 => 'iphone5',
        // ...
    ];

    /** 支付方式 */
    public static $payType = [
        1 => '支付宝支付',
        2 => '微信支付',
        3 => '银行转账', // 这个不能修改
        4 => '银联支付',
    ];
}
