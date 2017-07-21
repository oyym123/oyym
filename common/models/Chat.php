<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/19
 * Time: 13:59
 */

namespace common\models;

use Yii;
use common\helpers\Helper;

class Chat extends Base
{
    /** 获取环信token */
    public function getToken()
    {
        $token = Yii::$app->redis->get('hx_access_token');
        $application = Yii::$app->redis->get('hx_application');
        if ($token && $application) {
            return $token;
        } else {
            $data = [
                'grant_type' => 'client_credentials',
                'client_id' => Yii::$app->params['hx_client_id'],
                'client_secret' => Yii::$app->params['hx_client_secret'],
            ];
            $res = Helper::post2(Yii::$app->params['hx_api_url'] . 'token', json_encode($data));
            $result = json_decode($res, true);
            Yii::$app->redis->setex('hx_access_token', $result['expires_in'], $result['access_token']);
            Yii::$app->redis->setex('hx_application', $result['expires_in'], $result['application']);
            return Yii::$app->redis->get('hx_access_token');
        }
    }

    /** 获取header */
    public function getHeader()
    {
        $data[] = 'Authorization:Bearer ' . $this->getToken();
        return $data;
    }

    /** 用户注册 */
    public function register()
    {
        $user = User::findOne(['id' => Yii::$app->user->id]);
        $user->hx_password = uniqid();
        if ($user->save()) {
            $data = [
                'username' => User::hxUserName($user->id),
                'password' => $user->hx_password,
                'nickname' => $user->info ? $user->info->name : md5(time())
            ];
            $res = Helper::request(Yii::$app->params['hx_api_url'] . 'users', 'POST', json_encode($data), $this->getHeader());
            return $result = json_decode($res, true);
        }
    }

    /** 获取单个IM用户 */
    public function getOneIM()
    {
        $userName = User::hxUserName(2);
        $res = Helper::request(Yii::$app->params['hx_api_url'] . 'users/' . $userName, 'GET', [], $this->getHeader());
        return $result = json_decode($res, true);
    }

    /** 修改用户昵称 */
    public function changeNickName()
    {
        $user = User::findOne(['id' => Yii::$app->user->id]);
        $userName = User::hxUserName($user->id);
        $data = [
            'nickname' => $user->info ? $user->info->name : md5(time())
        ];
        $res = Helper::request(Yii::$app->params['hx_api_url'] . 'users/' . $userName, 'PUT', $data, $this->getHeader());
        return $result = json_decode($res, true);
    }

    /** 获取数据(默认没有body) */
    public static function request($url, $type)
    {
        $chat = new Chat();
        $res = Helper::request($url, $type, [], $chat->getHeader());
        return $result = json_decode($res, true);
    }


}