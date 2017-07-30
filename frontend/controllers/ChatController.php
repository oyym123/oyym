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

    public function init()
    {
        parent::init();
        if (empty(Yii::$app->user->id) && in_array(Yii::$app->requestedRoute, [
                'chat/all-user-get',
            ])
        ) {
            self::needHtmlLogin();
        }
    }

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

    /** 获取单个IM用户信息 */
    public function actionUserGet()
    {
        $chat = new TaoBaoOpenApi();
        $data = $chat->userGet(Yii::$app->user->id);
        $this->layout = 'blank';
        $t = json_decode($data, true);
        return $this->render('index', [
            'data' => $t['userinfos']['userinfos'][0],
        ]);
    }

    /** 发送消息 */
    public function actionSendMsg()
    {
        $chat = new TaoBaoOpenApi();
        $data = $chat->userGet(Yii::$app->user->id);
        $this->layout = 'blank';
        $t = json_decode($data, true);
        return $this->render('index', [
            'data' => $t['userinfos']['userinfos'][0],
            'touid' => Yii::$app->request->get('id')
        ]);
    }

    /** 获所有IM用户信息(不包括本身) */
    public function actionAllUserGet()
    {
        $chat = new TaoBaoOpenApi();
        $userIds = User::getAllUserId();
        $data = $chat->userGet(implode(',', array_column($userIds, 'id')));
        $this->layout = 'blank';
        $t = json_decode($data, true);
        // $this->showMsg($t['userinfos']['userinfos']);exit;
        return $this->render('all_user', [
            'data' => $t['userinfos']['userinfos'],
        ]);
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