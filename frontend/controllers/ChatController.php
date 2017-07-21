<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/19
 * Time: 14:16
 */

namespace frontend\controllers;

use Yii;
use common\helpers\Helper;
use common\models\Chat;
use common\models\User;
use frontend\components\WebController;

class ChatController extends WebController
{

    public function actionGetToken()
    {
        $chat = new Chat();
        self::showMsg($chat->getOneIM());
    }

    public function actionDeleteIm()
    {
        $userName = User::hxUserName(2);
        $url = Yii::$app->params['hx_api_url'] . 'users/' . $userName;
        self::showMsg(Chat::request($url, 'DELETE'));
    }

    public function actionChangeNickname()
    {
        $chat = new Chat();
        self::showMsg($chat->changeNickName());
    }
}