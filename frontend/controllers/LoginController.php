<?php

namespace frontend\controllers;

use app\helpers\Helper;
use common\models\BaseData;
use common\models\City;
use common\models\Invite;
use common\models\LoginForm;
use frontend\components\WebController;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class LoginController extends WebController
{
    public function init()
    {
        parent::init();
        if (empty($this->userId) && in_array(Yii::$app->requestedRoute, [
                'login/phone-scancode'
            ])
        ) {
            self::needLogin();
        }
    }

    public function actionIndex()
    {
        $data['LoginForm']['username'] = Yii::$app->request->post('user_name');
        $data['LoginForm']['password'] = Yii::$app->request->post('password');

        $model = new LoginForm();
        if ($model->load($data) && $model->login()) {
            $userInfo = $model->user->info;
            $oldToken = $model->user->getToken();

            $model->user->setToken();

            if ($model->user->save()) {
                Yii::$app->redis->hdel('token', $oldToken);
                Yii::$app->redis->hset('token', $model->user->getToken(), $model->user->id);
            }

            $city = new City();

            $data = [
                "user_id" => $model->user->id,
                "sex" => $userInfo->getSex(),
                "province" => $userInfo->province,
                "province_name" => $userInfo->province && ($city = $city->findOne(['id' => $userInfo->province])) ? $city->name : '',
                "city_name" => $userInfo->city && ($city = $city->findOne(['id' => $userInfo->city])) ? $city->name : '',
                "city" => $userInfo->city,
//                "county" => $city->findOne(['id' => $userInfo->county])->name,
                "nick_name" => $model->user ? $model->user->getName() : '', // ($field = $userInfo->getIdentName()) ? $userInfo->$field : '',
                "user_name" => Helper::formatMobile($model->user->username),
                "token" => $model->user->getToken(),
                'photo' => $userInfo->photoUrl()
            ];

//            Yii::$app->redis;
//            $session = Yii::$app->session;
            setcookie('51zs_id', /*$model->user->id*/
                1, time() + 86400 * 180, '/', $_SERVER['HTTP_HOST']);

            self::showMsg($data, $model->user->id);
        } else {
            self::showMsg('用户名或密码错误', -1);
        }
    }

    /** 退出 */
    public function actionLogout()
    {
        if (Yii::$app->user->logout()) {
//            $session = Yii::$app->session;
//            $session->remove('user_id');
//            setcookie('51zs_id', '', time() - 86400, '/', $_SERVER['HTTP_HOST']);
            self::showMsg('退出成功', 1);
        }

        self::showMsg('退出失败', -1);
    }
}
