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
        return ['Authorization' => 'Bearer' . $this->getToken()];
    }

    /** 用户注册 */
    public function register()
    {
        $password = uniqid();

        $user = User::findOne(['id' => Yii::$app->user->id]);
        $user->hx_password = $password;
        if ($user->save()) {
            $data = [
                'username' => $user->info ? $user->info->name : '',
                'password' => $password,
                'nickname' => '公子小白'
            ];
            $res = Helper::post2(Yii::$app->params['hx_api_url'] . 'users', json_encode($data), $this->getHeader());
            return $result = json_decode($res, true);
        }


    }

}