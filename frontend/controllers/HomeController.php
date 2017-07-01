<?php
namespace frontend\controllers;

use frontend\components\WebController;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

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
     * @SWG\Get(path="/demo/demo?debug=1",
     *   tags={"demo"},
     *   description="Author: lixinxin",
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
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
     * Desc: 获取宝贝分类
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/demo/demo?debug=1",
     *   tags={"demo"},
     *   description="Author: lixinxin",
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
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
