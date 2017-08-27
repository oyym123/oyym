<?php

namespace common\models;

use Yii;
use frontend\components\WebController;

/**
 * This is the model class for table "visit_amount".
 *
 * @property integer $id
 * @property string $user_ip
 * @property string $user_ip_address
 * @property string $interface
 * @property integer $phone_type
 * @property integer $visit_times
 * @property integer $created_at
 * @property integer $updated_at
 */
class VisitAmount extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visit_amount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_type', 'visit_times', 'created_at', 'updated_at'], 'integer'],
            [['user_ip'], 'string', 'max' => 50],
            [['user_ip_address'], 'string', 'max' => 255],
        ];
    }

    /** 获取用户地址 @OYYM */
    public static function getUserAddress()
    {
        $address = \common\helpers\Helper::ipToAddress(Yii::$app->request->userIP);
        return $address['region'] . $address['city'] . $address['county'];
    }

    /** 访问统计 */
    public static function createVisit()
    {
        $model = self::findOne(['user_ip' => Yii::$app->request->userIP, 'interface' => Yii::$app->requestedRoute]) ?: new VisitAmount();
        $model->user_ip = Yii::$app->request->userIP;
        $model->user_ip_address = self::getUserAddress();
        $model->interface = Yii::$app->requestedRoute;
        $model->visit_times = $model->visit_times ? $model->visit_times + 1 : 1;
        $model->phone_type = WebController::getAppTypeByUa();
        if (!$model->save()) {
            var_dump($model->getErrors());
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_ip' => 'User Ip',
            'user_ip_address' => 'User Ip Address',
            'phone_type' => 'Phone Type',
            'visit_times' => 'Visit Times',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
