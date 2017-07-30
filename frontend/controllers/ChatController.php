<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/19
 * Time: 14:16
 */

namespace frontend\controllers;

use common\models\TaoBaoOpenApi;
use Yii;
use common\helpers\Helper;
use common\models\Chat;
use common\models\User;
use frontend\components\WebController;

class ChatController extends WebController
{

//    public function actionGetToken()
//    {
//        $chat = new Chat();
//        self::showMsg($chat->getOneIM());
//    }
//
//    public function actionDeleteIm()
//    {
//        $userName = User::hxUserName(2);
//        $url = Yii::$app->params['hx_api_url'] . 'users/' . $userName;
//        self::showMsg(Chat::request($url, 'DELETE'));
//    }
//
//    public function actionChangeNickname()
//    {
//        $chat = new Chat();
//        self::showMsg($chat->changeNickName());
//    }

    /** 新增IM用户 */
    public function actionUserAdd()
    {

        $params = [
            'userid' => 'kk1',
            'password' => '123456',
            'nick' => '小王'
        ];

        $chat = new TaoBaoOpenApi();
        $data = $chat->userAdd(json_encode($params));
        self::showMsg($data);
    }

    /** 获取IM用户信息 */
    public function actionUserGet()
    {
        $chat = new TaoBaoOpenApi();
        $data = $chat->userGet('kk2');
        self::showMsg($data);
    }

    /** 删除IM用户 */
    public function actionUserDelete()
    {
        $chat = new TaoBaoOpenApi();
        $data = $chat->userDelete('kk');
        self::showMsg($data);
    }

    /** 推送自定义openim消息 */
    public function actionCustmsgPush()
    {
        $chat = new TaoBaoOpenApi();
        $data = $chat->CustmsgPush('kk');
        self::showMsg($data);
    }


}