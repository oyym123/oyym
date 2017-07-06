<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "user_address".
 *
 * @property integer $id
 * @property string $user_name
 * @property integer $user_id
 * @property string $lng
 * @property string $lat
 * @property string $postal
 * @property string $telephone
 * @property string str_address
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $area_id
 * @property integer street_id
 * @property integer $default_address
 * @property integer $street
 * @property integer $detail_address
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserAddress extends Base
{
    const NO_DEFAULT_ADDRESS = 0; //不是默认收货地址
    const DEFAULT_ADDRESS = 1; //默认收货地址

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_name', 'province_id', 'city_id', 'area_id', 'street_id', 'telephone', 'postal', 'detail_address', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'province_id', 'city_id', 'area_id', 'street_id', 'default_address', 'status', 'created_at', 'updated_at'], 'integer'],
            [['user_name', 'str_address', 'detail_address'], 'string', 'max' => 255],
            [['lng', 'lat'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_name' => '收货人名称',
            'user_id' => '用户ID',
            'lng' => '经度',
            'lat' => '纬度',
            'province_id' => '省',
            'city_id' => '市',
            'area_id' => '地区',
            'street_id' => '街道',
            'default_address' => '默认地址',
            'detail_address' => '详细地址',
            'telephone' => '电话号码',
            'postal' => '邮政编码',
            'status' => '状态',
            'str_address' => '完整地址',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /** 获取省名称 */
    public function getProvince()
    {
        return $this->hasOne(City::className(), ['id' => 'province_id']);
    }

    /** 获取市名称 */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /** 获取区名称 */
    public function getArea()
    {
        return $this->hasOne(City::className(), ['id' => 'area_id']);
    }

    /** 获取街道称 */
    public function getStreet()
    {
        return $this->hasOne(City::className(), ['id' => 'street_id']);
    }

    /** 设置用户地址 */
    public function setAddress($params)
    {
        $model = self::findOne(['id' => $params['id'], 'user_id' => Yii::$app->user->id]) ?: new UserAddress();
        $model->user_name = $params['user_name'];
        $model->user_id = Yii::$app->user->id;
        $model->lng = $params['lng'];
        $model->lat = $params['lat'];
        $model->province_id = $params['province_id'];
        $model->city_id = $params['city_id'];
        $model->area_id = $params['area_id'];
        $model->street_id = $params['street_id'];
        $model->detail_address = $params['detail_address'];
        $model->postal = $params['postal'];
        $model->telephone = $params['telephone'];
        $model->status = $params['status'];
        $model->created_at = time();
        $model->updated_at = time();
        $model->str_address = $this->mergeAddress($model);
        if (!$model->save()) {
            throw new Exception('地址保存失败!');
        }

        if (ArrayHelper::getValue($params, 'default_address') == self::DEFAULT_ADDRESS) {
            self::setDefaultAddress(['user_id' => $model->user_id, 'id' => $model->id]);
        }
    }

    /** 拼接省市县街道地址 */
    public function mergeAddress($model)
    {
        $address = [
            $model->province ? $model->province->name : '',
            $model->city ? $model->city->name : '',
            $model->area ? $model->area->name : '',
            $model->street ? $model->street->name : ''
        ];
        return implode('', $address);
    }

    /** 获取默认用户地址 */
    public static function getDefaultAddress()
    {
        return self::find()->where(['user_id' => Yii::$app->user->id])->
        andFilterWhere(['default_address' => self::DEFAULT_ADDRESS])->orderBy('created_at desc')->one();
    }

    /** 获取用户所有地址 */
    public static function getAddress()
    {
        return self::find()->where(['user_id' => Yii::$app->user->id])
            ->orderBy('created_at desc')->all();
    }

    /** 设置默认地址 */
    public static function setDefaultAddress($params)
    {
        $address = self::findOne(['user_id' => Yii::$app->user->id, 'default_address' => self::DEFAULT_ADDRESS]);
        if ($address) {
            $address->default_address = self::NO_DEFAULT_ADDRESS;
            if (!$address->save()) {
                throw new Exception('默认地址设置失败!');
            }
        }

        $defaultAddress = self::findOne(['id' => ArrayHelper::getValue($params, 'id'),
            'user_id' => Yii::$app->user->id]);
        $defaultAddress->default_address = self::DEFAULT_ADDRESS;
        if (!$defaultAddress->save()) {
            throw new Exception('默认地址设置失败!');
        }
    }
}
