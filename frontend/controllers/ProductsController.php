<?php
namespace frontend\controllers;

use common\models\Base;
use common\models\Collection;
use common\models\Image;
use common\models\Like;
use common\models\OrderProduct;
use common\models\Product;
use common\models\ProductType;
use common\models\Video;
use frontend\components\WebController;
use SebastianBergmann\Diff\LCS\TimeEfficientImplementation;
use Yii;
use yii\base\Exception;
use common\helpers\Helper;
use common\models\Comments;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * 1 * Products controller
 */
class ProductsController extends WebController
{
    public function init()
    {
        parent::init();
        if (empty($this->userId) && in_array(str_replace('products/', '', Yii::$app->requestedRoute), [
                'create', 'lottery', 'seller-product-list', 'buyer-product-list'
            ])
        ) {
            self::needLogin();
        }
    }

    /**
     * Name: actionCategory
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/products/category",
     *   tags={"产品"},
     *   summary="获取宝贝分类",
     *   description="Author: OYYM",
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionCategory()
    {
        self::showMsg(
            ProductType::getType()
        );
    }

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
     *     name="sort_type", in="query", required=true, type="string", default="tuijian",
     *     description="排序类型"
     *   ),
     *   @SWG\Parameter(
     *     name="keywords", in="query", required=false, type="string", default="电脑",
     *     description="搜索关键词"
     *   ),
     *   @SWG\Parameter(
     *     name="category", in="query", required=false, type="string", default="1",
     *     description="宝贝分类"
     *   ),
     *   @SWG\Parameter(
     *     name="offset", in="query", required=false, default="0", type="string",
     *     description="分页用的数据游标"
     *   ),
     *   @SWG\Parameter(
     *     name="user_id", in="query", required=false, default="0", type="string",
     *     description="取某个卖家的数据"
     *   ),
     *  @SWG\Parameter(
     *     name="ky-token", in="header", required=false, type="integer", default="1",
     *    ),
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   )
     * )
     */
    public function actionIndex()
    {
        $product = new Product();
        $params = [
            'sort_type' => Yii::$app->request->get('sort_type', 'danjia'),
            'keywords' => Yii::$app->request->get('keywords', ''),
        ];
        $layoutType = 0;
        if ($params['sort_type'] == 'danjia') {
            $layoutType = 1;
        }
        $data = [];
        list($products, $data['count']) = $product->apiSearch($params, $this->offset, 10);
        foreach ($products as $product) {
            $data['products_list'][] = [
                'id' => $product->id,
                'images' => $product->getImages(),
                'title' => $product->title,
                'contents' => $product->contents,
                'progress' => $product->progress ?: 0, // 众筹进度,里面数字是参与人数
                'total' => $product->total, // 总需人次
                'comments' => $product->comments,
                'like' => $product->likes,
                'layout_type' => $layoutType ? 1 : $product->listLayoutType(),//单价排序,布局默认为1
                'a_price' => $product->a_price ?: 0.00,
                // 布局类型
                'zongjia' => $product->total,
                'collection_flag' => $product->getIsCollection(),
                'like_flag' => $product->getIsLike(),
                'unit_price' => $product->unit_price,
                'end_time' => $product->end_time,
                'share_params' => [
                    'share_title' => '众筹夺宝',
                    'share_contents' => '夺宝达人!',
                    'share_link' => 'http://' . $_SERVER['HTTP_HOST'] . Url::to(['invite/signup', 'invite_id' => $this->userId]),
                    'share_img_url' => 'https://www.baidu.com/img/bd_logo1.png',
                ]
            ];
        }
        $this->showMsg($data);
    }


    /**
     * Name: actionSellerProductList
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-12
     * @SWG\Get(path="/products/seller-product-list",
     *   tags={"我的"},
     *   summary="我发布的",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="status", in="query", required=true, type="integer", default="0",
     *     description="宝贝状态,传的值有: 全部=0 || 未上架=10 || 进行中=20 || 待揭晓=30 || 已揭晓=40, 这儿没有 待发货,代签收"
     *   ),
     *   @SWG\Parameter(name="offset", in="query", required=true, type="integer", default="0",
     *     description="数据游标"
     *   ),
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *       response=200,description="
     *          product_count=宝贝总数
     *          product_list=宝贝列表
     *              layout=布局类型[数量模式_卖家_未上架 || 数量模式_卖家_进行中 || 数量模式_卖家_待揭晓 || 数量模式_卖家_已揭晓 || 时间模式_卖家_未上架 || 时间模式_卖家_进行中 || 时间模式_卖家_待揭晓 || 时间模式_卖家_已揭晓]
     *              product_id=宝贝id
     *              order_id=订单id
     *              title=标题
     *              img=宝贝头图
     *              total=总需人次
     *              residual_total=剩余人次
     *              residual_time=结束时间
     *              progress=进度
     *              publish_countdown=揭晓倒计时
     *              a_price=一口价
     *              unit_price=单价
     *              status=状态 [下架 || 进行中 || 待揭晓 || 已揭晓]
     *              actions=数组下是字典
     *                  [
     *                      title=上架
     *                      url=up_sell
     *                  ],
     *                  [
     *                      title=下架
     *                      url=down_sell
     *                  ],
     *                  [
     *                      title=编辑
     *                      url=edit
     *                  ],
     *                  [
     *                      title=删除
     *                      url=edit
     *                  ]
     *              url=链接地址[跳转到宝贝详情页=product, 跳转到订单详情页=order]
     *              order_award_count=已参与人次"
     *   )
     * )
     */

    public function actionSellerProductList($status)
    {
        $productModel = new Product();
        list($sellerAllProducts, $count) = $productModel->sellerProducts([
            'created_by' => Yii::$app->user->identity->id,
            'status' => $status,
            'created_by' => $this->userId,
            'offset' => Yii::$app->request->get('offset', 0)
        ]);

        $data = [
            'product_count' => $count,
            'product_list' => $sellerAllProducts
        ];

        self::showMsg($data);
    }

    /**
     * Name: actionBuyerProductList
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-12
     * @param $status
     * @SWG\Get(path="/products/buyer-product-list",
     *   tags={"我的"},
     *   summary="我参与的",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="status", in="query", required=true, type="integer", default="全部",
     *     description="宝贝状态,传的值有: 全部 || 正在进行 || 待揭晓 || 已揭晓, 这儿没有 待发货,代签收"
     *   ),
     *   @SWG\Parameter(name="offset", in="query", required=true, type="integer", default="0",
     *     description="数据游标"
     *   ),
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *       response=200,description="
     *          product_count=宝贝总数
     *          product_list=宝贝列表
     *              layout=布局类型[数量模式_买家_进行中 || 数量模式_买家_待揭晓 || 数量模式_买家_已揭晓 || 时间模式_买家_进行中 || 时间模式_买家_待揭晓 || 时间模式_买家_已揭晓]
     *              product_id=宝贝id
     *              order_id=订单id
     *              title=标题
     *              img=宝贝头图
     *              total=总需人次
     *              residual_total=剩余人次
     *              residual_time=结束时间
     *              progress=进度
     *              publish_countdown=揭晓倒计时
     *              a_price=一口价
     *              unit_price=单价
     *              status=状态 [下架 || 进行中 || 待揭晓 || 已揭晓]
     *              actions=数组下是字典
     *                  [
     *                      title=上架
     *                      url=up_sell
     *                  ],
     *                  [
     *                      title=下架
     *                      url=down_sell
     *                  ],
     *                  [
     *                      title=编辑
     *                      url=edit
     *                  ],
     *                  [
     *                      title=删除
     *                      url=edit
     *                  ]
     *              url=链接地址[跳转到宝贝详情页=product, 跳转到订单详情页=order]
     *              order_award_count=已参与人次"
     *   )
     * )
     */
    public function actionBuyerProductList($status)
    {
        $productModel = new Product();
        list($sellerAllProducts, $count) = $productModel->buyerProducts([
            'created_by' => Yii::$app->user->identity->id,
            'status' => $status,
            'created_by' => $this->userId,
            'offset' => Yii::$app->request->get('offset', 0)
        ]);

        $data = [
            'product_count' => $count,
            'product_list' => $sellerAllProducts
        ];

        self::showMsg($data);
    }

    /**
     * @SWG\Post(path="/products/create",
     *   tags={"产品"},
     *   summary="发布宝贝",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="data",
     *     in="formData",
     *     default="{'freight':'运费','model':1,'title':'Iphone9','contents':'\u4e70\u5b8c\u5c31\u5403\u571f:)','images':[{'name':'\u6d4b\u8bd5\u56fe\u7247','url':'demo321'},{'name':'\u6d4b\u8bd5\u56fe\u72472','url':'demo456'}],'videos':[{'name':'\u6d4b\u8bd5\u89c6\u98911','url':'demo321'}],'address':{'lat':'0.232512','lng':'1.2335432','detail_address':'\u6cb3\u5317\u7701\u5eca\u574a\u5e02\u71d5\u90ca\u9547'},'total':10000,'a_price':8000,'type_id':2,'start_time':1499392688,'end_time':1499692688,'unit_price':1}",
     *     description= "发布产品需要的数据，可在http://www.bejson.com/jsonviewernew/上解析",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *       response=200,description="
     *          product_id=1"
     *   )
     * )
     */
    public function actionCreate()
    {
        $data = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        $images = json_decode($data['images'], true);
        $videos = json_decode($data['videos'], true);
        $address = json_decode($data['address'], true);
        try {
            if ($data['unit_price'] > $data['a_price'] && !empty($data['a_price'])) {
                throw new Exception('单价不能大于一口价');
            }
            if (count($images) > 6) {
                throw new Exception('最多上传6张图片');
            }
            if (count($videos) > 1) {
                throw new Exception('最多上传1个视频');
            }
            $product = new Product();
            $product->title = $data['title'];
            $product->contents = $data['contents'];
            $product->detail_address = $address['detail_address'];
            $product->lat = $address['lat'];
            $product->lng = $address['lng'];
            $product->model = $data['model'];
            $product->created_by = $this->userId;
            $product->freight = $data['freight'];
            $product->status = Product::STATUS_IN_PROGRESS;
            $product->created_at = time();
            $product->updated_at = time();
            $product->total = $data['total'];
            $product->unit_price = $data['unit_price'];
            $product->a_price = $data['a_price'] ?: '';
            $product->start_time = $data['start_time'] ?: '';
            $product->end_time = $data['end_time'] ?: '';
            $product->type_id = $data['type_id'];

            if (!$product->save()) {
                throw new Exception('宝贝发布失败');
            }
            foreach ($images as $image) {
                $params = [
                    'name' => $image['name'],
                    'type' => Image::TYPE_PRODUCT,
                    'type_id' => $product->id,
                    'url' => $image['url'],
                    'size_type' => Image::SIZE_MEDIUM,
                    'status' => Base::STATUS_ENABLE,
                ];
                Image::setImage($params);
            }

            foreach ($videos as $video) {
                $params = [
                    'name' => $video['name'],
                    'type' => Video::TYPE_PRODUCT,
                    'type_id' => $product->id,
                    'url' => $video['url'],
                    'size_type' => Video::SIZE_HD,
                    'status' => Base::STATUS_ENABLE,
                ];
                Video::setVideo($params);
            }
            $transaction->commit();

            self::showMsg([
                'product_id' => $product->id
            ], 1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
    }

    /**
     * @param $id
     * @SWG\Get(path="/products/view",
     *   tags={"产品"},
     *   summary="宝贝详情页",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="id", in="query", required=true, type="integer", default="1",
     *     description="产品ID",
     *   ),
     *   @SWG\Response(
     *       response=200,description="
     *          id=宝贝id
     *          images=相册
     *          videos=视频
     *          title=宝贝标题
     *          contents=宝贝介绍
     *          progress=众筹进度
     *          like=被赞数
     *          like_flag=是否已赞
     *          collection_flag=是否已收藏
     *          comments=评论数
     *          layout_type=布局类型
     *          unit_price=宝贝单价
     *          a_price=宝贝一口价
     *          total=需要参与人次
     *          order_award_count=已参与人次
     *          remaining=剩余人数,在数量模式时使用
     *          start_time=宝贝开始时间
     *          end_time=宝贝结束时间
     *          announced_mode=揭晓模式(卖家用户的待揭晓页面，显示“我来揭晓”, 买家用户的待揭晓页面，显示“请等待系统揭晓”)
     *              layout=当值为1时,意味着可以点击,则title=”我来揭晓”,当值为2时,意味着不可点击,则title=”请等待系统揭晓”
     *              title=文案目前只有'我来揭晓'和'请等待系统揭晓'
     *          luck_user=中奖用户
     *              user_img=头像地址
     *              luck_number=幸运号码
     *              list=列表数组
     *                  title=获得者
     *                  value=小李
     *          share_params=分享参数字典
     *              share_title=众筹夺宝
     *              share_contents=夺宝达人
     *              share_link=链接
     *              share_img_url=图标url
     *          comment_count=评论总数
     *          comment_list=评论列表数组
     *              user_count=评论人数
     *              list
     *                  id=评论id
     *                  user_photo=评论用户头像地址
     *                  comment_line_id=评论的上级id,用于区分我评论的谁的评论
     *                  like_count=赞数统计
     *                  user_name=用户名
     *                  contents=评论内容
     *                  date=评论时间
     *                  reply=回复此评论的评论
     *                      id=评论id
     *                      user_name=用户名
     *                      contents=评论内容
     *          publish_countdown=揭晓截止时间"
     *   )
     * )
     */

    public function actionView()
    {
        $item = $this->findModel(['id' => Yii::$app->request->get('id')]);
        $participants = $item->getJoinCount(); //已参加人数
        $data = [
            'id' => $item->id,
            'images' => $item->getImages(),
            'videos' => $item->getVideos(),
            'title' => $item->title,
            'contents' => $item->contents,
            'progress' => $item->progress ?: 0, // 众筹进度
            'like' => $item->likes, // 喜欢
            'model_type' => $item->model,
            'collection' => $item->collections, // 收藏
            'like_flag' => $item->getIsLike(), // 喜欢标志
            'comments' => $item->comments, // 评论
            'layout_type' => $item->viewLayoutType(), // 布局类型
            'unit_price' => $item->unit_price,
            'freight' => $item->freight,
            'a_price' => $item->a_price ?: 0, // 一口价,若有则为大于0 的值,没有则为 0
            'total' => $item->total, // 需要参与人次
            'remaining' => $item->total - $participants, // 剩余人次
            'start_time' => date('Y-m-d H:i', $item->start_time), // 开始时间
            'end_time' => date('Y-m-d H:i', $item->end_time), // 结束时间
            'announced_mode' => $item->viewAnnouncedType(), // 揭晓模式(卖家用户的待揭晓页面，显示“我来揭晓”, 买家用户的待揭晓页面，显示“请等待系统揭晓”)
            'order_award_count' => $item->order_award_count ?: 0, // 已参与人次
            'luck_user' => [
                'user_img' => $item->getLuckUserPhoto(),
                'luck_number' => $item->orderAwardCode ? $item->orderAwardCode->code : 10000001,
                'list' => [
                    [
                        'title' => '获得者:',
                        'value' => $item->getLuckUserName(),
                        [
                            'title' => '参与方式:',
                            'value' => "一口价{$item->a_price}元购买"
                        ],
                        [
                            'title' => '购买时间:',
                            'value' => date('Y-m-d H:i:s', $item->order ? $item->order->created_at : 0)
                        ],
                    ]
                ]
            ],
            'share_params' => [
                'share_title' => '众筹夺宝',
                'share_contents' => '夺宝达人!',
                'share_link' => 'http://' . $_SERVER['HTTP_HOST'] . \yii\helpers\Url::to(['invite/signup', 'invite_id' => $this->userId]),
                'share_img_url' => 'https://www.baidu.com/img/bd_logo1.png',
            ],
            'collection_flag' => $item->getIsCollection(),
            //'can_buy' => $item->isCanBuy(),
            'comment_count' => $item->comments,
            'comment_list' => Comments::getProduct($item->id, 0),
            'sale_user' => [
                'img' => $item->userInfo ? $item->userInfo->photoUrl($item->created_by) : Yii::$app->params['defaultPhoto'],
                'name' => $item->user ? $item->user->getName() : '',
                'zhima' => '芝麻信用:700',
                'intro' => Helper::tranTime($item->created_at) . "发布于 " . $item->detail_address . ", 来到众筹夺宝"
                    . ($item->user ? $item->user->getJoinTime($item->user->created_at) : 0) . "天了,成功卖出"
                    . ($item->userInfo ? $item->userInfo->sold_products : 0) . "件商品",
            ],
            'actions' => $item->buttonType(),
            'publish_countdown' => $item->getPublishCountdown(), // 揭晓倒计时以秒为单位
        ];
        self::showMsg($data);
    }

    /**
     * @SWG\Get(path="/products/participate-record",
     *   tags={"产品"},
     *   summary="用户参与记录",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="产品Id",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="
     *         user_img = 用户头像
     *         user_name = 用户姓名
     *         address = 用户地址
     *         ip = 用户ip
     *         times = 用户参与次数
     *         date = 参与的日期
     *     "
     *   )
     * )
     */
    public function actionParticipateRecord()
    {
        $product = $this->findModel(['id' => Yii::$app->request->get('id')]);
        $data = [];
        $data['list'] = [];
        foreach ($product->getSuccessOrderProduct($this->offset) as $item) {
            $ip = $item->order->ip ?: '';
            $data['list'][] = [
                'id' => $item->id,
                'user_img' => ($x = $item->buyer->info) ? $x->photoUrl($item->buyer_id) : Yii::$app->params['defaultPhoto'],
                'user_name' => $item->buyer->getName(),
                'address' => $item->order->user_address,
                'ip' => substr($ip, 0, strrpos($ip, '.')) . '.***',
                'times' => count($item->orderAward),
                'date' => date('Y-m-d H:i', $item->created_at)
            ];
        }
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

    /**
     * Name: actionLottery
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-18
     * @SWG\Get(path="/products/lottery",
     *   tags={"产品"},
     *   summary="卖家我来开奖接口",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="id", in="query", required=true, type="integer", default="1",
     *     description="宝贝id"
     *   ),
     *   @SWG\Parameter(name="ky-token", in="header", required=false, type="integer", default="1",
     *     description="ky-token"
     *   ),
     *   @SWG\Response(
     *       response=200,description="
     *          code=0
     *          msg=您成功抽中一名中奖用户; 当code=-1时,返回的是错误提示,例如:不能重复开奖, 开奖成功后需刷新宝贝详情"
     *   )
     *
     * )
     */
    public function actionLottery($id)
    {
        $product = $this->findModel(['id' => $id]);

        list($code, $msg) = $product->openLottery();

        self::showMsg($msg, $code);
    }

    /**
     * Name: actionLotteryArithmetic
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-18
     * @SWG\Get(path="/products/lottery-arithmetic",
     *   tags={"产品"},
     *   summary="抽奖计算详情",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="id", in="query", required=true, type="integer", default="1",
     *     description="宝贝id"
     *   ),
     *   @SWG\Response(
     *       response=200,description="
     *          order_award_count=参与人次
     *          number_a=12345689
     *          number_b=123456890
     *          product_title=iphone 6s 9成新 便宜出了 800包邮不议价,手快有手慢无
     *          luck_number=幸运号码"
     *   )
     * )
     */
    public function actionLotteryArithmetic($id)
    {
        $product = $this->findModel(['id' => $id]);
        $r = [
            'order_award_count' => $product->order_award_count ?: 0, // 已参与人次
            'number_a' => $product->getNumberAModel()->sum('created_at'),
            'number_b' => $product->random_code,
            'product_title' => $product->title,
            'luck_number' => $product->orderAwardCode ? $product->orderAwardCode->code : '',
        ];

        self::showMsg($r);
    }

    /**
     * Name: actionUpSell
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-19
     * @SWG\Get(path="/products/up-sell",
     *   tags={"产品"},
     *   summary="上架",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="id", in="query", required=true, type="integer", default="1",
     *     description="宝贝id"
     *   ),
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *       response=200,description="
     *          code=0
     *          msg=上架成功"
     *   )
     * )
     */
    public function actionUpSell($id)
    {
        $product = $this->findModel(['id' => $id]);
        list($code, $msg) = $product->upSell();
        self::showMsg($msg, $code);
    }

    /**
     * Name: actionDownSell
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-19
     * @SWG\Get(path="/products/up-sell",
     *   tags={"产品"},
     *   summary="下架",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="id", in="query", required=true, type="integer", default="1",
     *     description="宝贝id"
     *   ),
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *       response=200,description="
     *          code=0
     *          msg=下架成功"
     *   )
     * )
     */
    public function actionDownSell($id)
    {
        $product = $this->findModel(['id' => $id]);
        list($code, $msg) = $product->downSell();
        self::showMsg($msg, $code);
    }
}
