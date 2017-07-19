<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/19
 * Time: 14:16
 */

namespace frontend\controllers;

use common\models\Chat;
use frontend\components\WebController;

class ChatController extends WebController
{

    public function actionGetToken()
    {
        $chat = new Chat();
        self::showMsg($chat->getOneIM());
    }

}