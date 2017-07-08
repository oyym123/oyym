<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/6/29
 * Time: 13:54
 */

namespace frontend\controllers;

use common\models\Base;
use common\models\City;
use common\models\UserAddress;
use frontend\components\WebController;
use Yii;

class UserAddressController extends WebController
{
    public function init()
    {
        parent::init();
        if (empty($this->userId)) {
            self::needLogin();
        }
    }

    /**
     * @SWG\Post(path="/user-address/area-info",
     *   tags={"用户地址"},
     *   summary="省市县数据",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="id",
     *     in="formData",
     *     default="1",
     *     description="0 = 下放省级数据，后根据传来的id下放对应级别下的数据",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="ky-token",
     *     in="header",
     *     default="1",
     *     description="用户ky-token",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   )
     * )
     */
    public function actionAreaInfo()
    {
        if (Yii::$app->request->post('id')) {
            $model = City::find()->andFilterWhere(['upid' => Yii::$app->request->post('id')])->all();
        } else {
            $model = City::findAll(['level' => 1]);
        }
        $data = [];
        foreach ($model as $item) {
            $data[] = [
                'id' => $item->id,
                'name' => $item->name,
                'level' => $item->level,
            ];
        }
        self::showMsg($data);
    }

    /**
     * @SWG\Post(path="/user-address/create",
     *   tags={"用户地址"},
     *   summary="新增/修改用户地址",
     *   description="Author: OYYM",
     * @SWG\Parameter(
     *     name="id",
     *     in="formData",
     *     default="1",
     *     description="需要修改的地址id",
     *     required=false,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="user_name",
     *     in="formData",
     *     default="欧阳先生",
     *     description="收货人姓名",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="province_id",
     *     in="formData",
     *     default="14",
     *     description="省ID(江西省)",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="city_id",
     *     in="formData",
     *     default="213",
     *     description="市ID(景德镇)",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="area_id",
     *     in="formData",
     *     default="2426",
     *     description="区、县ID(珠山区)",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="street_id",
     *     in="formData",
     *     default="19639",
     *     description="街道ID(新厂街道)",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="detail_address",
     *     in="formData",
     *     default="天子庄园12号一单元",
     *     description="地址详细位置",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="telephone",
     *     in="formData",
     *     default="13161057804",
     *     description="手机号",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="postal",
     *     in="formData",
     *     default="333000",
     *     description="邮政编码",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="default_address",
     *     in="formData",
     *     default="1",
     *     description="1 = 是默认地址",
     *     required=false,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="ky-token",
     *     in="header",
     *     default="1",
     *     description="用户ky-token",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   )
     * )
     */
    public function actionCreate()
    {
        $params = [
            'user_name' => Yii::$app->request->post('user_name'),
            'province_id' => Yii::$app->request->post('province_id'),
            'city_id' => Yii::$app->request->post('city_id'),
            'area_id' => Yii::$app->request->post('area_id'),
            'street_id' => Yii::$app->request->post('street_id'),
            'lng' => Yii::$app->request->post('lng'),
            'lat' => Yii::$app->request->post('lat'),
            'telephone' => Yii::$app->request->post('telephone'),
            'detail_address' => Yii::$app->request->post('detail_address'),
            'postal' => Yii::$app->request->post('postal'),
            'default_address' => Yii::$app->request->post('default_address'),
            'status' => Base::STATUS_ENABLE
        ];
        $model = new UserAddress();
        $params['id'] = Yii::$app->request->post('id') ?: '';
        $model->setAddress($params);
        self::showMsg('地址保存成功', 1);
    }

    /**
     * @SWG\Get(path="/user-address/set-default-address",
     *   tags={"用户地址"},
     *   summary="设置默认收货地址",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="用户地址ID",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="ky-token",
     *     in="header",
     *     default="1",
     *     description="用户ky-token",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionSetDefaultAddress()
    {
        UserAddress::setDefaultAddress(['id' => Yii::$app->request->get('id')]);
        self::showMsg('默认地址设置成功', 1);
    }

    /**
     * @SWG\Get(path="/user-address/get-default-address",
     *   tags={"用户地址"},
     *   summary="获取用户默认地址",
     *   description="Author: OYYM",
     * @SWG\Parameter(
     *     name="ky-token",
     *     in="header",
     *     default="1",
     *     description="用户ky-token",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionGetDefaultAddress()
    {
        $address = UserAddress::getDefaultAddress();
        $data = [
            'id' => $address->id,
            'user_name' => $address->user_name,
            'postal' => $address->postal,
            'str_address' => $address->str_address,
            'detail_address' => $address->detail_address,
            'telephone' => $address->telephone
        ];
        self::showMsg($data);
    }

    /**
     * @SWG\Get(path="/user-address/get-address",
     *   tags={"用户地址"},
     *   summary="获取用户所有地址",
     *   description="Author: OYYM",
     *  @SWG\Parameter(
     *     name="ky-token",
     *     in="header",
     *     default="1",
     *     description="用户ky-token",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionGetAddress()
    {
        $data = [];
        foreach (UserAddress::getAddress() as $address) {
            $data[] = [
                'id' => $address->id,
                'user_name' => $address->user_name,
                'postal' => $address->postal,
                'str_address' => $address->str_address,
                'detail_address' => $address->detail_address,
                'telephone' => $address->telephone,
                'default_address' => $address->default_address
            ];
        }
        self::showMsg($data);
    }
}