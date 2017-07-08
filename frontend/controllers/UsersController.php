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
 * Users controller
 */
class UsersController extends WebController
{
    /**
     * Name: actionFollowCategory
     * Desc: 关注的分类
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/users/follow-category",
     *   tags={"用户"},
     *   summary= "关注的分类",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   )
     * )
     */
    public function actionFollowCategory()
    {
        self::showMsg([
            [
                'id' => 1,
                'title' => '电子产品',
                'is_follow' => 1 // 已关注为1未关注为0
            ],
            [
                'id' => 2,
                'title' => '服装',
                'is_follow' => 0
            ]
        ]);
    }

    /**
     * Name: actionFollowCategoryOrCancel
     * Desc: 关注或取消关注
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/users/follow-category-or-cancel",
     *   tags={"用户"},
     *   summary= "关注或取消关注",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *       response=200, description="successful operation"
     *   )
     * )
     */
    public function actionFollowCategoryOrCancel()
    {
        self::showMsg('已关注');
        self::showMsg('已取消关注');
    }

    /**
     * Name: actionSortType
     * Desc: 首页排序类型列表
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/users/sort-type",
     *   tags={"用户"},
     *   summary= "首页排序类型列表",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *     response=200, description="successful operation"
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
     * @SWG\Get(path="/users/category",
     *   tags={"用户"},
     *   summary= "获取宝贝分类",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *      response=200, description="successful operation"
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
