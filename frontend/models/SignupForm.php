<?php
namespace frontend\models;

use common\models\TaoBaoOpenApi;
use common\models\UserInfo;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->hx_password = uniqid();
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }

    /**
     * Signs user up.
     * 设置用户user_info,以及注册OpenIM
     *
     */
    public function setUserInfo($user)
    {
        $userInfo = new UserInfo();
        $userInfo->user_id = $user->id;
        $userInfo->name = $user->name;

        $params = [
            'userid' => $user->id,
            'password' => uniqid(),
            'nick' => $user->name
        ];

        $chat = new TaoBaoOpenApi();
        $data = $chat->userAdd(json_encode($params));
        return $userInfo->save() ? $userInfo : null;
    }

}
