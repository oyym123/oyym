<?php
namespace frontend\controllers;

use common\models\Base;
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
     * @SWG\Get(path="/products?debug=1",
     *   tags={"产品"},
     *   summary="产品列表",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *      response=200, description="successful operation"
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
                    'progress' => '80', //
                    'progress_type' => "time || number",
                    'like' => 123,
                    'comment' => '12',
                    'danjia' => '12', // 单价
                    'zongjia' => '12', // 总价
                    'yikoujia' => '12', // 一口价
                    'end_time' => '12', // 时间
                    'share_params' => [
                        'share_title' => '众筹夺宝',
                        'share_contents' => '夺宝达人!',
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


    public function actionCreate()
    {
        $data = [
            'images' => [
                [
                    'name' => '测试图片',
                    'url' => 'demo321',
                ],
                [
                    'name' => '测试图片2',
                    'url' => 'demo456',
                ]
            ],

            'videos' => [
                [
                    'name' => '测试视频1',
                    'url' => 'demo321',
                ],
                [
                    'name' => '测试视频2',
                    'url' => 'demo321',
                ]
            ],
            'quantity' => 8,
            ''

        ];

        $params = [
            'name' => Yii::$app->request->post('name'),
            'type' => Yii::$app->request->post('type'),
            'type_id' => Yii::$app->request->post('id'),
            'url' => Yii::$app->request->post('url'),
            'size_type' => Yii::$app->request->post('size', Image::SIZE_BIG),
            'status' => Base::STATUS_ENABLE,
            'sort' => Yii::$app->request->post('sort', 8),
            'limit' => Yii::$app->request->post('limit', 1)
        ];


    }


    public function actionView($id)
    {
        $this->showMsg([

        ]);
    }


}
