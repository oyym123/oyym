<?php
namespace frontend\controllers;

use common\models\Base;
use frontend\components\WebController;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;
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
     * @SWG\Get(path="/products",
     *   tags={"首页"},
     *   summary="产品列表",
     *   description="Author: lixinxin",
     *   consumes={"application/json", "application/xml"},
     *   produces={"application/json", "application/xml"},
     *   @SWG\Parameter(
     *     name="sort_type",
     *     in="query",
     *     default="tuijian",
     *     description="排序类型",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="keywords",
     *     in="query",
     *     default="电脑",
     *     description="搜索关键词",
     *     required=false,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="category",
     *     in="query",
     *     default="1",
     *     description="宝贝分类",
     *     required=false,
     *     type="string",
     *   ),
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *
     *
     *   )
     * )
     */
    public function actionIndex()
    {
        self::showMsg([
            'sort_type' => 'tuijian',
            'count' => 200,
            'products_list' => [
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
                    'progress' => '80', // 众筹进度
                    'model_type' => 1, // 众筹模式
                    'layout_type' => "1", // 布局类型
                    'like' => 123,
                    'comment' => '12',
                    'unit_price' => '12', // 单价
                    'zongjia' => '12', // 总价
                    'a_price' => '12', // 一口价
                    'end_time' => '12', // 时间
                    'layout_type' => $this->listlayoutType([]), // 布局类型
                    'share_params' => [
                        'share_title' => '众筹夺宝',
                        'share_contents' => '夺宝达人!',
                        'share_link' => 'http://' . $_SERVER['HTTP_HOST'] . Url::to(['invite/signup', 'invite_id' => $this->userId]),
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
                    'title' => '保时捷跑车便宜卖了',
                    'contents' => "保时捷跑车便宜卖了,保时捷跑车便宜卖了,保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了",
                    'progress' => '80', // 进度
                    'model_type' => "1", // 众筹模式
                    'layout_type' => "2", // 布局类型
                    'like' => 123,
                    'comment' => '12',
                    'unit_price' => '12', // 单价
                    'zongjia' => '12', // 总价
                    'a_price' => '12', // 一口价
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
                    'title' => '保时捷跑车便宜卖了',
                    'contents' => "保时捷跑车便宜卖了,保时捷跑车便宜卖了,保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了",
                    'progress' => '80', // 众筹
                    'model_type' => 1, // 众筹模式
                    'layout_type' => "3", // 布局类型
                    'like' => 123,
                    'comment' => '12',
                    'unit_price' => '12', // 单价
                    'zongjia' => '12', // 总价
                    'a_price' => '12', // 一口价
                    'end_time' => '12', // 时间
                    'layout_type' => "2", // 布局类型
                    'share_params' => [
                        'share_title' => '众筹夺宝',
                        'share_contents' => '夺宝达人!',
                        'share_link' => 'http://' . $_SERVER['HTTP_HOST'] . yii\helpers\Url::to(['invite/signup', 'invite_id' => $this->userId]),
                        'share_img_url' => 'https://www.baidu.com/img/bd_logo1.png',
                    ]
                ]
            ]
        ]);
    }


    /** 首页布局类型,根据宝贝属性判定客户端需要调用哪种的布局类型 */
    public function listLayoutType($product)
    {
        return 1;
    }

    /**
     * Name: actionCreate
     * Desc: 添加宝贝, 由客户端发起宝贝的添加
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-05
     * @SWG\Get(path="/demo/demo",
     *   tags={"demo"},
     *   summary="添加宝贝",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */

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

    /** 宝贝详情接口下放布局样式id, 用于控制客户端展示不同的布局  */
    public function viewLayoutType($product)
    {

    }

    public function actionView($id)
    {
        $item = $this->findModel(['id' => $id]);
        $params['user_id'] = $this->userId;
        $params['type'] = Collection::TYPE_VIDEO;
        $params['type_id'] = $id;

        $collectionFlag = Collection::collectionFlag($params) ? Collection::COLLECTED : Collection::NOT_COLLECT;

        $data = [
            'id' => __LINE__,
            'status' => '',
            'layout_type' => '布局类型',
            'images' => [
                [
                    'id' => __LINE__,
                    'url' => 'https://www.baidu.com/img/bd_logo1.png',
                ],
                [
                    'id' => __LINE__,
                    'url' => 'https://www.baidu.com/img/bd_logo1.png',
                ],
                [
                    'id' => __LINE__,
                    'url' => 'https://www.baidu.com/img/bd_logo1.png',
                ]
            ],
            'title' => $item->title,
            'contents' => "保时捷跑车便宜卖了,保时捷跑车便宜卖了,保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了",
            'progress' => '80', // 众筹进度
            'model_type' => $item->model,
            'layout_type' => "1", // 布局类型
            'like' => 123, // 喜欢
            'comment' => '12', // 评论
            'unit_price' => '12', // 单价
            'a_price' => '12', // 一口价
            'end_time' => '12', // 结束时间
            'need_total' => 1000, // 需要参与人次
            'remaining' => 11, // 剩余人次
            'all_total' => 3245, // 总参与人次
            'layout_type' => $this->listLayoutType([]), // 布局类型
            'share_params' => [
                'share_title' => '众筹夺宝',
                'share_contents' => '夺宝达人!',
                'share_link' => 'http://' . $_SERVER['HTTP_HOST'] . yii\helpers\Url::to(['invite/signup', 'invite_id' => $this->userId]),
                'share_img_url' => 'https://www.baidu.com/img/bd_logo1.png',
            ],

            'address' => "北京 朝阳区",
            'intro' => $this->getIntro(2000),
            'peiyou_apply_personal_data' => [
                'tips_title' => '请补充资料',
                'tips_contents' => '培优计划是为学生量身定制的名师服务计划,需要学生尽可能详细的补充个人学习成绩信息等,点击"下一步"开始完善资料吧~',
            ],
            'collection_flag' => $collectionFlag,
            'can_buy' => $item->isCanBuy(),
            'comment_count' => $item->user_count,
            'comment_list' => $item->getComments([
                'skip' => 0,
                'psize' => 5,
            ]),
            'video_url' => '',
            'video_img' => '',
            'sale_user' => [
                'img' => '',
                'name' => '',
                'zhima' => '芝麻信用:700',
                'intro' => '来到众筹夺宝20天了,成功卖出30件商品',
            ],
            'publish_countdown' => '7200', // 揭晓倒计时以秒为单位
            'luck_user' => [
                'user_img' => '',
                'luck_number' => '3707863272837',
                'list' => [
                    [
                        'title' => '获得者:',
                        'value' => '李新新'
                    ],
                    [
                        'title' => '参与人次:',
                        'value' => '50'
                    ],
                    [
                        'title' => '揭晓时间:',
                        'value' => '2017-07-08 08:12:22'
                    ],
                ]
            ], // 中奖人信息
            'luck_user2' => [
                'user_img' => '',
                'luck_number' => '',
                'list' => [
                    [
                        'title' => '获得者:',
                        'value' => '李新新'
                    ],
                    [
                        'title' => '参与方式:',
                        'value' => '一口价500元购买'
                    ],
                    [
                        'title' => '购买时间:',
                        'value' => '2017-07-08 08:12:22'
                    ],
                ]
            ], // 中奖人信息

        ];

        $this->showMsg($data);
    }

    /** 取产品实体 */
    protected function findModel($params)
    {
        if (($model = Product::findOne($params)) !== null) {
            return $model;
        } else {
            self::showMsg('宝贝不存在', -1);
        }
    }

}
