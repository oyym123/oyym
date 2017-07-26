<?php

namespace frontend\controllers;

use app\helpers\Helper;
use common\models\City;
use common\models\SchoolExcel;
use common\models\User;
use common\models\UserInfo;
use frontend\components\WebController;
use Yii;

// id,parent_id,code,name,
class CityController extends WebController
{

    /**
     * Name: actionIndex
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-24
     * @SWG\Get(path="/city",
     *   tags={"省市"},
     *   summary="一次性获取所有省市和地区",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *       response=200,description="
     *          详细参数见返回结果"
     *   )
     * )
     */
    public function actionIndex()
    {
        $cacheKey = '省市区';
        $cacheData = Yii::$app->cache->get($cacheKey);

        if ($cacheData) {
            self::showMsg($cacheData);
        }

        $provinces = City::findAll(['upid' => 0]);

        $datas['list'] = [];
        foreach ($provinces as $key => $province) {
            $data = [
                'id' => $province->id,
                'name' => $province->name,
                'city' => [],
            ];
            if ($province->child) {
                foreach ($province->child as $city) {
                    $cities = [
                        'id' => $city->id,
                        'name' => $city->name,
                        'diqu' => [],
                    ];

                    if ($city->child) {
                        foreach ($city->child as $diqu) {
                            $cities['diqu'][] = [
                                'id' => $diqu->id,
                                'name' => $diqu->name,
                            ];
                        }
                    }
                    $data['city'][] = $cities;
                    unset($cities);
                }
            }
            $datas['list'][] = $data;
            unset($data);
        }

        Yii::$app->cache->set($cacheKey, $datas);

        self::showMsg($datas);
    }

}