<?php
namespace frontend\controllers;

use common\models\Base;
use common\models\Collection;
use common\models\Image;
use common\models\Like;
use common\models\Product;
use common\models\ProductType;
use common\models\Video;
use frontend\components\WebController;
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
 * Products controller
 */
class ProductsController extends WebController
{

    public function init()
    {
        parent::init();
        if (empty($this->userId) && in_array(Yii::$app->requestedRoute, [
                'products/create'
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
     *   @SWG\Parameter(
     *     name="skip",
     *     in="query",
     *     default="0",
     *     description="分页用的数据游标",
     *     required=false,
     *     type="string",
     *   ),
     *  @SWG\Parameter(
     *     name="ky-token",
     *     in="header",
     *     default="1",
     *     description="用户ky-token",
     *     required=false,
     *     type="integer",
     *    ),
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *
     *
     *   )
     * )
     */
    public function actionIndex()
    {
        $product = new Product();
        $skip = intval(Yii::$app->request->get('skip', 0));
        $psize = intval(Yii::$app->request->get('psize', 10));
        $params['sort_type'] = Yii::$app->request->get('sort_type', 'danjia');
        $data = [];
        list($products, $data['count']) = $product->apiSearch($params, "$skip, $psize");
        $params['type'] = Collection::TYPE_PRODUCT;
        $params['status'] = Collection::COLLECT;
        foreach ($products as $product) {
            $params['type_id'] = $product->id;
            $collectionFlag = Collection::collectionFlag($params) ? Collection::COLLECT : Collection::NOT_COLLECT;
            $likeFlag = Like::likeFlag($params) ? Like::STATUS_ENABLE : Like::STATUS_DISABLE;
            $data['products_list'][] = [
                'id' => $product->id,
                'images' => Image::getImages(['type' => Image::TYPE_PRODUCT, 'type_id' => $product->id]),
                'model_type' => $product->model,
                'title' => $product->title,
                'contents' => $product->contents,
                'progress' => $product->getProgress(100), // 众筹进度,里面数字是参与人数
                'all_total' => $product->total, // 众筹进度,里面数字是参与人数
                'comment' => $product->comments,
                'like' => $product->likes,
                'collection' => $product->collections,
                'layout_type' => $product->listLayoutType(), //
                'a_price' => $product->a_price,
                // 布局类型
                'zongjia' => $product->total,
                'collection_flag' => $collectionFlag,
                'like_flag' => $likeFlag,
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
                    'share_params' => [
                        'share_title' => '众筹夺宝',
                        'share_contents' => '夺宝达人!',
                        'share_link' => 'http://' . $_SERVER['HTTP_HOST'] . Url::to(['invite/signup', 'invite_id' => $this->userId]),
                        'share_img_url' => 'https://www.baidu.com/img/bd_logo1.png',
                    ]
                ]
            ]
        ]);
    }

    /**
     * @SWG\Post(path="/products/create",
     *   tags={"产品"},
     *   summary="发布宝贝",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="data",
     *     in="formData",
     *     default="{'model':1,'title':'Iphone9','contents':'\u4e70\u5b8c\u5c31\u5403\u571f:)','images':[{'name':'\u6d4b\u8bd5\u56fe\u7247','url':'demo321'},{'name':'\u6d4b\u8bd5\u56fe\u72472','url':'demo456'}],'videos':[{'name':'\u6d4b\u8bd5\u89c6\u98911','url':'demo321'}],'address':{'lat':'0.232512','lng':'1.2335432','detail_address':'\u6cb3\u5317\u7701\u5eca\u574a\u5e02\u71d5\u90ca\u9547'},'total':10000,'a_price':8000,'type_id':2,'start_time':1499392688,'end_time':1499692688,'unit_price':1}",
     *     description= "发布产品需要的数据，可在http://www.bejson.com/jsonviewernew/上解析",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="ky-token",
     *     in="header",
     *     default="1",
     *     description="用户ky-token",
     *     required=true,
     *     type="integer",
     *    ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionCreate()
    {
        //  $data = json_encode($data);
        $data = Yii::$app->request->post();
        // $data = json_decode($data, true);
        $transaction = Yii::$app->db->beginTransaction();
        $images = json_decode($data['images'], true);
        $videos = json_decode($data['videos'], true);
        $address = json_decode($data['address'], true);
        try {
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
            $product->user_id = $this->userId;
            $product->created_by = $this->userId;
            $product->status = Product::STATUS_IN_PROGRESS;
            $product->created_at = time();
            $product->updated_at = time();
            $product->total = $data['total'];
            $product->unit_price = $data['unit_price'];
            $product->a_price = $data['a_price'];
            $product->start_time = $data['start_time'];
            $product->end_time = $data['end_time'];
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
            self::showMsg('宝贝发布成功！', 1);
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
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="产品ID",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionView()
    {
        $item = $this->findModel(['id' => Yii::$app->request->get('id')]);
        $params['type'] = Collection::TYPE_PRODUCT;
        $params['type_id'] = $item->id;
        $collectionFlag = Collection::collectionFlag($params) ? Collection::COLLECT : Collection::NOT_COLLECT;
        $participants = 100; //已参加人数
        $data = [
            'id' => $item->id,
            'images' => Image::getImages(['type' => Image::TYPE_PRODUCT, 'type_id' => $item->id]),
            'videos' => Video::getVideos(['type' => Video::TYPE_PRODUCT, 'type_id' => $item->id]),
            'title' => $item->title,
            'contents' => $item->contents,
            'progress' => '80', // 众筹进度
            'model_type' => $item->model,
            'like' => $item->likes, // 喜欢
            'collection' => $item->collections, // 喜欢
            'comments' => $item->comments, // 评论
            'layout_type' => $item->viewLayoutType(), // 布局类型
            'unit_price' => $item->unit_price,
            'a_price' => $item->a_price ?: 0, // 一口价,若有则为大于0 的值,没有则为 0
            'need_total' => $item->total, // 需要参与人次
            'remaining' => $item->total - $participants, // 剩余人次
            'start_time' => $item->start_time, // 开始时间
            'end_time' => $item->end_time, // 结束时间
            'announced_mode' => $item->viewAnnouncedType(), // 揭晓模式
            'all_total' => 3245, // 总参与人次
            'luck_user' => [
                'user_img' => '',
                'luck_number' => '3707863272837',
                'list' => [
                    [
                        'title' => '获得者:',
                        'value' => '李新新'
                    ],
                    [
                        'title' => '参与方式:',
                        'value' => "一口价{$item->a_price}元购买"
                    ],
                    [
                        'title' => '购买时间:',
                        'value' => '2017-07-08 08:12:22'
                    ],
                ]
            ],
            'share_params' => [
                'share_title' => '众筹夺宝',
                'share_contents' => '夺宝达人!',
                'share_link' => 'http://' . $_SERVER['HTTP_HOST'] . \yii\helpers\Url::to(['invite/signup', 'invite_id' => $this->userId]),
                'share_img_url' => 'https://www.baidu.com/img/bd_logo1.png',
            ],
            'address' => $item->detail_address,
            'intro' => '',
            'collection_flag' => $collectionFlag,
            //    'can_buy' => $item->isCanBuy(),
            'comment_count' => $item->comments,
            'comment_list' => Comments::getProduct($item->id, 0, 5),
            'sale_user' => [
                'img' => '',
                'name' => '',
                'zhima' => '芝麻信用:700',
                'intro' => '来到众筹夺宝20天了,成功卖出30件商品',
            ],
            'publish_countdown' => '7200', // 揭晓倒计时以秒为单位
        ];

        self::showMsg($data);
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
