<?php

namespace frontend\controllers;

use common\models\Shipping;
use frontend\components\WebController;
use Yii;

class ShippingController extends WebController
{
    /**
     * Name: actionShippingCompany
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-29
     * @SWG\Get(path="/shipping/companies",
     *   tags={"我的"},
     *   summary="获取快递公司",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *       response=200,description="
     *          list = 数字加字典[
     *              code = shunfeng
     *              title = 顺丰
     *          ]
     *     "
     *   )
     * )
     */
    public function actionCompanies()
    {
        $shippingCompany = Shipping::shippingCompany();

        $data['list'] = [];
        foreach ($shippingCompany as $key => $val) {
            $data['list'][] = [
                'code' => $key,
                'title' => $val,
            ];
        }
        self::showMsg($data);
    }
}