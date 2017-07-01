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
 * Products controller
 */
class ProductsController extends WebController
{
    /**
     * Name: actionIndex
     * Desc: 宝贝列表页接口
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
    public function actionIndex()
    {
        self::showMsg([
            'sort_type' => 'tuijian',
            'products' => [
                [
                    'id' => __LINE__,
                    'images' => [
                        [
                            'id' => __LINE__,
                            'url' => 'https://www.baidu.com/img/bd_logo1.png',
                        ],
                        [
                            'id' => __LINE__,
                            'url' => 'https://www.baidu.com/img/bd_logo1.png',
                        ]
                    ],
                    'title' => '保时捷跑车便宜卖了',
                    'contents' => "保时捷跑车便宜卖了,保时捷跑车便宜卖了,保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了",
                    'progress' => '80%', //
                    'progress_type' => "time || number",
                    'like' => 123,
                    'comment' => '12',
                    'danjia' => '12', // 单价
                    'zongjia' => '12', // 总价
                    'yikoujia' => '12', // 一口价
                    'end_time' => '12', // 时间
                    'share_params' => [
                        'share_title' => '高招App',
                        'share_contents' => '下载高招App圆您一个名校的梦想!',
                        'share_link' => 'http://' . $_SERVER['HTTP_HOST'] . yii\helpers\Url::to(['invite/signup', 'invite_id' => $this->userId]),
                        'share_img_url' => 'https://www.baidu.com/img/bd_logo1.png',
                    ]
                ],
                [
                    'id' => __LINE__,
                    'images' => [
                        [
                            'id' => __LINE__,
                            'url' => 'https://www.baidu.com/img/bd_logo1.png',
                        ],
                        [
                            'id' => __LINE__,
                            'url' => 'https://www.baidu.com/img/bd_logo1.png',
                        ]
                    ],
                ]
            ]
        ]);
    }

    public function actionView($id) {
        $this->showMsg([

        ]);
    }



}
