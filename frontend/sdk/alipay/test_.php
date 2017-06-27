<?php
date_default_timezone_set('Asia/shanghai');

require_once("alipay.config.php");
require_once("lib/alipay_core.function.php");
require_once("lib/alipay_rsa.function.php");

$apiParams = [
    'service' => 'mobile.securitypay.pay',
    'out_trade_no' => date('YmdHis'),
    '_input_charset' => 'utf-8',
    'total_fee' => 0.03,
    'subject' => '这是商品名称',
    'body' => '这是购买的线下培训服务',
    'partner' => '2088801047131045',
    'notify_url' => 'http://api.51zzzs.cn/',
    'payment_type' => 1,
    'goods_type' => 0, // 0 虚拟 1 实物
    'seller_id' => '1@diyixue.com',
];
$apiParams = $apiParams2 = argSort($apiParams);

foreach ($apiParams as $key => $val) {
    $apiParams[$key] = '"' . $val . '"';
}

$apiParams = createLinkstring($apiParams);

$signData = rsaSign($apiParams, 'key/rsa_private_key.pem');
//$signData = rsaDecrypt($signData, 'key/rsa_private_key.pem');



echo $apiParams . '&sign_type="RSA"&sign="' . urlencode($signData) . '"';
exit;

$a = ['params' => $apiParams . '&sign=' . $signData];
echo json_encode($a);