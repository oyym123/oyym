<?php
namespace frontend\controllers;

use frontend\components\WebController;

/**
 * @SWG\Get(path="/home/sort-type",
 *   tags={"首页"},
 *   summary="首页排序类型列表",
 *   description="Author: lixinxin",
 *   @SWG\Response(
 *      response=200, description="successful operation"
 *   )
 * )
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
                'id' => 'tuijian',
                'title' => '推荐'
            ],
            [
                'id' => 'jindu',
                'title' => '进度'
            ],
            [
                'id' => 'danjia',
                'title' => '单价'
            ],
            [
                'id' => 'zongjia',
                'title' => '总价'
            ],
            [
                'id' => 'yikoujia',
                'title' => '一口价'
            ],
            [
                'id' => 'shijian',
                'title' => '时间'
            ],
            [
                'id' => 'zuixin',
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
                'id' => __LINE__,
                'title' => '电子产品'
            ],
            [
                'id' => 2,
                'title' => '服装'
            ]
        ]);
    }


}
