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
        if (empty($this->userId) && in_array(Yii::$app->requestedRoute, [
                'user-address/create',
            ])
        ) {
            //    self::needLogin();
        }
    }

    /**
     * @SWG\Post(path="/user-address/area-info?debug=1",
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
     *   @SWG\Response(
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
     * @SWG\Post(path="/user-address/create?debug=1",
     *   tags={"用户地址"},
     *   summary="新增用户地址",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     default="天子庄园12号一单元",
     *     description="地址详细位置",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="province_id",
     *     in="formData",
     *     default="1",
     *     description="省ID",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="city_id",
     *     in="formData",
     *     default="1",
     *     description="市ID",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="area_id",
     *     in="formData",
     *     default="1",
     *     description="区、县ID",
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
     *   @SWG\Response(
     *   )
     * )
     */
    public function actionCreate()
    {
        $params = [
            'name' => Yii::$app->request->post('name'),
            'province_id' => Yii::$app->request->post('province_id'),
            'city_id' => Yii::$app->request->post('city_id'),
            'area_id' => Yii::$app->request->post('area_id'),
            'lng' => Yii::$app->request->post('lng'),
            'lat' => Yii::$app->request->post('lat'),
            'default_address' => Yii::$app->request->post('default_address'),
            'status' => Base::STATUS_ENABLE
        ];
        $model = new UserAddress();
        $model->setAddress($params);
        self::showMsg('地址保存成功', 1);
    }


}