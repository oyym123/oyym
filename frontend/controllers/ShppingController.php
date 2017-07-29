<?php

namespace frontend\controllers;

use common\models\Base;
use common\models\Shipping;
use yii\base\Exception;
use frontend\components\WebController;
use Yii;

class ShippingController extends WebController
{
    /**
     * Name: actionShippingCompany
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-29
     * @SWG\Get(path="/orders/shipping-company",
     *   tags={"我的"},
     *   summary="获取快递公司",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="sn", in="query", required=true, type="string", default="201707101223",
     *     description=""
     *   ),
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
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
    public function actionSellerShipping()
    {
        $shippingCompany = Shipping::shippingCompany();

        $data['list'] = [];
        foreach ($shippingCompany as $key => $val) {
            $data['list'][] = [
                'code' => $key,
                'title' => $val,
            ];

            self::showMsg($data);
        }

    }
}