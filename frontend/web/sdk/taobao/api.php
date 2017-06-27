<?php
$alidayuParams = require(__DIR__ . '/../../../../common/config/params-local.php');

define('APP_KEY', empty($alidayuParams['alidayu.appkey']) ? '' : $alidayuParams['alidayu.appkey']);
define('SECRET_KEY', empty($alidayuParams['alidayu.secretKey']) ? '' : $alidayuParams['alidayu.secretKey']);

if (!check()) {
    echo 'sign check error';
    exit;
}

include "TopSdk.php";

date_default_timezone_set('Asia/Shanghai');

$c = new TopClient;
$c->appkey = APP_KEY;
$c->secretKey = SECRET_KEY;
$req = new AlibabaAliqinFcSmsNumSendRequest;

$req->setExtend(isset($_POST['extend']) ? $_POST['extend'] : '123');
$req->setSmsType("normal");
$req->setSmsFreeSignName($_POST['type']);
$req->setSmsParam(json_encode(['code' => $_POST['code'], 'product' => $_POST['product']]));
$req->setRecNum($_POST['mobile']);
$req->setSmsTemplateCode($_POST['template']);

/*
$req->setExtend(isset($_POST['extend']) ? $_POST['extend'] : '123');
$req->setSmsType("normal");
// 注意这儿
$req->setSmsFreeSignName('登录验证');
$req->setSmsParam(json_encode(['code' => '123', 'product' => '51zs']));
$req->setRecNum('18606615070');
$req->setSmsTemplateCode('SMS_5018034');
*/
$resp = $c->execute($req);

echo json_encode($resp);

function check()
{
    if (empty($_POST)) {
        return false;
    }

    $sign = $_POST['sign'];
    unset($_POST['sign']);

    ksort($_POST);

    $md5 = md5(implode(',', $_POST) . SECRET_KEY);

    return $md5 == $sign;
}

?>