<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/29
 * Time: 15:15
 */

namespace common\models;

use Yii;

require_once(Yii::getAlias('@app') . "/sdk/taobao/TopSdk.php");

class TaoBaoOpenApi extends Base
{
    public function getKey()
    {
        date_default_timezone_set('Asia/Shanghai');
        $c = new \TopClient;
        $c->appkey = Yii::$app->params['alidayu_appkey'];
        $c->secretKey = Yii::$app->params['alidayu_secretKey'];
        return $c;
    }


    /** IM新增用户 */
    public function userAdd($params)
    {
        $req = new \OpenimUsersAddRequest;
        $req->setUserinfos($params);
        $resp = $this->getKey()->execute($req);
        return json_encode($resp);
    }

    /** IM获取用户信息 */
    public function userGet($params)
    {
        $req = new \OpenimUsersGetRequest;
        $req->setUserids($params);
        $resp = $this->getKey()->execute($req);
        return json_encode($resp);
    }

    /** IM删除用户 */
    public function userDelete($params)
    {
        $req = new \OpenimUsersDeleteRequest;
        $req->setUserids($params); //需要删除的用户列表，多个用户用半角逗号分隔，最多一次可以删除100个用户
        $resp = $this->getKey()->execute($req);
        return json_encode($resp);
    }

    public function CustmsgPush($params)
    {
        $req = new \OpenimCustmsgPushRequest;
        $custmsg = new \CustMsg;
        $custmsg->from_user = "3";
        $custmsg->to_appkey = "0";
        $custmsg->to_users = "[\"1\",\"2\",'3','4','5']";
        $custmsg->summary = "客户端最近消息里面显示的消息摘要";
        $custmsg->data = "123456788";
        $custmsg->aps = "{\"alert\":\"ios apns push\"}";
        $custmsg->apns_param = "apns推送的附带数据";
        $custmsg->invisible = "1";
        $custmsg->from_nick = "sender_nick";
        $custmsg->from_taobao = "0";
        $req->setCustmsg(json_encode($custmsg));
        $resp = $this->getKey()->execute($req);
        return json_encode($resp);

    }


    /** 阿里大于短信 */
    public function aliDaYu()
    {
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend(isset($_POST['extend']) ? $_POST['extend'] : '123');
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($_POST['type']);
        $req->setSmsParam(json_encode(['code' => $_POST['code'], 'product' => $_POST['product']]));
        $req->setRecNum($_POST['mobile']);
        $req->setSmsTemplateCode($_POST['template']);
        $resp = $this->getKey()->execute($req);
        return json_encode($resp);
    }


}