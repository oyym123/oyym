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
        return ['Authorization' => 'Bearer ' . $this->getToken()];
    }

    /** 用户注册 */
    public function register()
    {
        $user = User::findOne(['id' => 2]);
        $user->hx_password = uniqid();
        if ($user->save()) {
            $data = [
                'username' => User::hxUserName($user->id),
                'password' => $user->hx_password,
                'nickname' => $user->info ? $user->info->name : md5(time())
            ];
            $res = Helper::post2(Yii::$app->params['hx_api_url'] . 'users', json_encode($data), $this->getHeader());
            return $result = json_decode($res, true);
        }
    }

    /** 获取单个IM用户 */
    public function getOneIM()
    {
        //  return $this->getHeader();
        $userName = User::hxUserName(2);
        $res = Helper::get(Yii::$app->params['hx_api_url'] . 'users/' . $userName, $this->getHeader());
        return $result = json_decode($res, true);
    }


}