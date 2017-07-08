<?php
namespace frontend\controllers;

use frontend\components\WebController;

/**
 * Home controller
 */
class HomeController extends WebController
{

    /**
     * Name: actionSortType
     * Desc: 首页排序类型列表
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/home/sort-type",
     *   tags={"首页"},
     *   summary="首页排序类型列表",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   )
     * )
     */
    public function actionSortType()
    {
        self::showMsg([
            [
                'id' => 1,
                'title' => '推荐'
            ],
            [
                'id' => 1,
                'title' => '进度'
            ],
            [
                'id' => 1,
                'title' => '单价'
            ],
            [
                'id' => 1,
                'title' => '总价'
            ],
            [
                'id' => 1,
                'title' => '一口价'
            ],
            [
                'id' => 1,
                'title' => '时间'
            ],
            [
                'id' => 1,
                'title' => '最新'
            ],

        ]);
    }

    /**
     * Name: actionCategory
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/home/category",
     *   tags={"分类"},
     *   summary="获取宝贝分类",
     *   description="Author: OYYM",
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionCategory()
    {
        self::showMsg([
            [
                'id' => 1,
                'title' => '电子产品'
            ],
            [
                'id' => 2,
                'title' => '服装'
            ]
        ]);
    }


}
