<?php

/**
 * @link http://www.51zzzs.cn/
 * @copyright 2016 中国自主招生网
 * @author lixinxin@zgzzzs.com
 */

namespace frontend\controllers;

use app\helpers\Helper;
use common\models\Comments;
use common\models\Order;
use common\models\OrderLog;
use common\models\OrderPay;
use common\models\Product;
use common\models\ProductImage;
use common\models\UploadForm;
use common\models\UserAddress;
use frontend\components\WebController;
use Yii;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * RegionController implements the CRUD actions for City model.
 */
class OrdersController extends WebController
{

    public function init()
    {
        parent::init();
        if (empty($this->userId)) {
            self::needLogin();
        }
    }

    public $pay = [
        'alipay' => '',
        "wx_sign" => "",
        "wx_timestamp" => "",
        "wx_partner_id" => "",
        "wx_package" => "",
        "wx_nonce_str" => "",
        "yl_tn" => "",
        "wx_prepay_id" => "",
    ];

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get'],
                ],
            ],
        ];
    }

    /** 订单列表 */
    public function actionIndex()
    {
        $order = new Order();
        $comment = new Comments();
        $params = Yii::$app->request->get();
        $params['skip'] = intval(Yii::$app->request->get('skip', 0));
        $params['psize'] = intval(Yii::$app->request->get('psize', 10));
        $params['user_id'] = $this->userId;

        list($items, $count) = $order->apiSearch($params);

        $data = [
            'title' => '我的订单',
            'total' => $count,
            'list' => []
        ];
        $flag = 1;
        $freightCount = 0;
        $productCountFlag = 0;
        foreach ($items as $item) {
            $products = $item->product;
            $p = [];
            foreach ($products as $product) {
                $params = [
                    'product_id' => $product->pid,
                    'type' => Comments::TYPE_VIDEO,
                    'user_id' => $this->userId,
                    'order_id' => $product->order_id,
                ];

                if (!$comment->commentFlag($params) && $item->isCanComment()) {
                    $commentFlag = 0;
                    $flag = 0;
                } else {
                    $commentFlag = 1;
                    $flag = 1;
                }

                $freightCount += ($product->product ? $product->product->freight : 0);
                $countFlag = $product->product ? (($x = $product->product->productType) ? $x->use_count : 0) : 0;
                $productCountFlag += $countFlag;
                $p[] = [
                    'id' => $product->pid, // 商品id
                    'img' => ProductImage::getOne(ProductImage::USE_FOR_LIST, $product->pid, false),
                    'title' => $product->title,
                    'price' => '￥' . floatval($product->price),
                    'count' => $product->count,
                    'count_flag' => $countFlag,
                    'comment_flag' => $commentFlag,
                ];
            }

            if (empty($p) && $this->isAndroid()) {
                // 为了Android不崩
                $p[] = [
                    'id' => '',
                    'img' => '',
                    'title' => '',
                    'price' => '',
                    'count' => '',
                    'count_flag' => '',
                    'comment_flag' => '',
                ];
            }
            $data['list'][] = [
                'id' => $item->id,
                'sn' => $item->sn,
                'flag_comment' => $flag,//外部判断是否能进行评价
                'can_delete' => $item->isCanDelete() ? 1 : 0,
                'freight' => '￥' . $freightCount,
                'product_count_flag' => $productCountFlag ? 1 : 0,
                'status_id' => $item->status,
                'status' => $item->getStatus(),
                'created_at' => date('Y-m-d H:i:s', $item->created_at),
                'pay_amount' => '￥' . floatval($item->pay_amount),
                'products' => $p
            ];
        }

        self::showMsg($data);
    }

    /** 重新支付订单 */
    public function actionReConfirm()
    {
        $order = $this->findOrderModel(['sn' => Yii::$app->request->post('sn'), 'user_id' => $this->userId]);
        Yii::$app->user->identity = $order->userEntity = $this->getApiUser();

        $products = [];
        foreach ($order->product as $op) {
            $products[] = [
                'id' => $op->getId(), // 商品id
                'action' => $op->product ? $op->product->getTypeField() : null,
                'title' => $op->title,
                'img' => $op->product ? $op->product->headImg() : '',
                'product_attribute_info' => '',
                'price' => '￥' . floatval($op->price),
                'count' => $op->count
            ];
        }

        if ($order->userCoupon && $order->userCoupon->coupon) {
            $order->amountDesc[] = [
                'name' => $order->userCoupon->coupon->title,
                'price' => '- ￥' . $order->userCoupon->coupon->price,
            ];
        }
        $data = [
            'title' => '支付',
            'user_name' => substr_replace(ArrayHelper::getValue($order->userEntity, 'username'), '****', 3, 4),
            'pay_amount' => '￥' . floatval($order->pay_amount),
            'product_amount' => '￥' . floatval($order->product_amount),
            'default_pay_type' => '支付宝支付',
            'pay_types' => $order->getPayTypes(Yii::$app->request->post('pay_ids', '["支付宝支付","微信支付"]')),
            'pay_status' => $order->status,
            'amount' => $order->amountDesc ?: ($this->isAndroid() ? [
                [
                    'name' => '',
                    'price' => '',
                ]
            ] : null),
            'products' => $products
        ];

        if (strpos(Yii::$app->request->post('pay_ids'), '微信支付') !== false) {
            $data['default_pay_type'] = '微信支付';
        }
        self::showMsg($data);
    }

    /** 验证提交的数据 */
    public function checkProduct()
    {
        $products = json_decode(Yii::$app->request->post('products'), true);

        $r = [];

        foreach ($products as $product) {
            if (!empty($product['id'])) {
                $productModel = Product::find()->where(['id' => $product['id']])->one();
                if ($productModel && $productModel->isCanBuy()) {
                    // 判断是否可以购买
                    $r[] = [
                        'product' => $productModel,
                        'count' => $product['count'],
                        'buy_type' => $product['buy_type'], // 购买方式 unit_price=众筹参与,a_price=一口价
                    ];
                }
            }
        }

        if (empty($r)) {
            self::showMsg('宝贝不存在', -1);
        }

        return $r;
    }

    /**
     * Name: actionConfirm
     * Desc: 提交订单前的确认
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-07
     * @SWG\Get(path="/orders/submit",
     *   tags={"demo"},
     *   summary="提交订单前的确认",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(
     *     products="[{'id':'1234','count':'1','buy_type':'a_price || unit_price'}, {'id':'231','count':'1','buy_type':'a_price || unit_price'}]",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation",
     *   )
     * )
     */
    public function actionConfirm()
    {
        // 检查宝贝
        $products = $this->checkProduct();

        $order = new Order();

        Yii::$app->user->identity = $order->userEntity = $this->getApiUser();

        $order->confirmPrice($products);

        $dataProducts = [];
        foreach ($products as $key => $item) {
            if ($item['product']->isCanbuy() == false) {
                self::showMsg('活动已结束, 不允许购买', -1);
            }

            $dataProducts[] = [
                'id' => $item['product']->id,
                'title' => $item['product']->title,
                'img' => $item['product']->headImg(),
                'price' => '￥' . $item['product']->$item['buy_type'],
                'count' => $item['count']
            ];
        }

        if (empty($dataProducts)) {
            self::showMsg('宝贝不存在', -1);
        }

        $data = [
            'title' => '确认订单',
            'user_name' => substr_replace(ArrayHelper::getValue($order->userEntity, 'username'), '****', 3, 4),
            'pay_amount' => '￥' . floatval($order->payAmount),
            'product_amount' => '￥' . floatval($order->productsAmount),
            'default_pay_type' => '支付宝支付',
            'pay_types' => $order->getPayTypes(Yii::$app->request->post('pay_ids', '["支付宝支付","微信支付"]')),
            'pay_terms_word' => '', // 付款说明
            'pay_terms' => '', // 是否展示 付款说明
            'amount' => $order->amountDesc ?: ($this->isAndroid() ? [
                [
                    'name' => '',
                    'price' => '',
                ]
            ] : null),
            'products' => $products,
            'address' => [
                'username' => '',
                'mobile' => '',
                'detail' => ''
            ]
        ];

        // 取默认收货人地址
        $userAddress = UserAddress::getDefaultAddress();
        if ($userAddress) {
            $data['address'] = [
                'username' => $userAddress->user_name,
                'mobile' => $userAddress->telephone,
                'detail' => UserAddress::mergeAddress($userAddress)
            ];
        }

        if (strpos(Yii::$app->request->post('pay_ids'), '微信支付') !== false) {
            $data['default_pay_type'] = '微信支付';
        }

        self::showMsg($data);
    }

    /**
     * Name: actionSubmit
     * Desc: 提交订单
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-07
     * @SWG\Get(path="/orders/submit",
     *   tags={"demo"},
     *   summary="提交订单",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(
     *     products="[{'id':'1234','count':'1','buy_type':'a_price || unit_price'}, {'id':'231','count':'1','buy_type':'a_price || unit_price'}]",
     *     pay_type="alipay",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation",
     *   )
     * )
     */
    public function actionSubmit()
    {
        // 检查sku
        $products = $this->checkProduct();

        $order = new Order();

        Yii::$app->user->identity = $order->userEntity = $this->getApiUser();

        $order->setPayType(Yii::$app->request->post('pay_type'));

        $order->confirmPrice($products);

        $dataProducts = [];
        foreach ($products as $key => $item) {
            if ($item['product']->isCanbuy() == false) {
                self::showMsg('活动已结束, 不允许购买', -1);
            }

            $dataProducts[] = [
                'id' => $item['product']->id,
                'title' => $item['product']->title,
                'img' => $item['product']->headImg(),
                'price' => '￥' . $item['product']->$item['buy_type'],
                'count' => $item['count']
            ];
        }

        if (empty($dataProducts)) {
            self::showMsg('产品不存在', -1);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $newOrder = $order->create();

            $order->saveProducts($newOrder);
            $order->saveCoupon($newOrder);

            // 获取支付参数
            $pay = array_merge($this->pay, $newOrder->getPayParams());
            $pay['sn'] = $newOrder->sn;

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
//            print_r($e->getMessage());
//            exit;
            self::showMsg($e->getMessage(), -1);
        }

        self::showMsg($pay);
    }

    /** 订单支付成功 */
    public function actionPaymentSuccess()
    {
        $order = $this->findOrderModel(['sn' => Yii::$app->request->get('sn'), 'user_id' => $this->userId]);

        $params = [];
//      if ($this->isIos() && in_array($this->getAppVersion(), Yii::$app->params['unDisplayVideosInIos'])) {
        if ($this->isWaitCheckInIos()) {
            // 苹果审核专用, 不取收费的视频
            $params['freeProduct'] = '0.00';
        }
        foreach ($order->product as $item) {
            $params['xingshi'] = $item ? (Product::getGuessLikeProductType($item->product->type ?: 12)) : [];
        }
        $data = [
            'id' => $order->id,
            'pay_type' => $order->getPayType(),
            'pay_amount' => '￥' . floatval($order->pay_amount),
        ];
        $data['video_course'] = [
            'list' => Product::getGuessLikeProduct($params),
        ];

        self::showMsg($data);
    }


    /** 订单详情 */
    public function actionView()
    {
        $comment = new Comments();
        $order = $this->findOrderModel(['sn' => Yii::$app->request->get('sn'), 'user_id' => $this->userId]);
        $order->user;

        $products = [];
        $flag = 1;
        foreach ($order->product as $op) {
            $params = [
                'product_id' => $op->pid,
                'type' => Comments::TYPE_VIDEO,
                'user_id' => $this->userId,
                'order_id' => $order->id,
            ];

            if (!$comment->commentFlag($params) && $order->isCanComment()) {
                $commentFlag = 0;
                $flag = 0;
            } else {
                $commentFlag = 1;
            }

            $countFlag = $op->product ? (($x = $op->product->productType) ? $x->use_count : '') : '';
            $products[] = [
                'id' => $op->getId(), // 商品id
                'action' => $op->product ? $op->product->getTypeField() : null,
                'title' => $op->title,
                'img' => ProductImage::getOne(ProductImage::USE_FOR_LIST, $op->pid, false),
                'product_attribute_info' => ($x = json_decode($op->product_attribute_info)) ? $x->name : '',
                'price' => '￥' . $op->price,
                'count' => $op->count,
                'count_flag' => $countFlag,
                'comment_flag' => $commentFlag
            ];
        }

        if (empty($products) && $this->isAndroid()) {
            // 为了Android不蹦
            $products[] = [
                'id' => '',
                'img' => '',
                'title' => '',
                'price' => '',
                'product_attribute_info' => '',
                'count' => '',
                'action' => '',
                'comment_flag' => '',
            ];
        }

        if ($order->userCoupon && $order->userCoupon->coupon) {
            $order->amountDesc[] = [
                'name' => $order->userCoupon->coupon->title,
                'price' => '- ￥' . $order->userCoupon->coupon->price,
            ];
        }

        $status = $order->getStatus();

        if ($this->isAndroid() && $this->getAppVersion() <= '4.6.6') {
            if ($order->getStatus() == '交易关闭') {
                $status = '已取消';
            } elseif ($order->getStatus() == '交易成功') {
                $status = '已付款';
            } else {
                $status = $order->getStatus();
            }
        }

        $data = [
            'id' => $order->id,
            'sn' => $order->sn,
            'tel' => '4009191918',
            'flag_comment' => $flag,//外部判断是否能进行评价
            'top_icon' => $order->getTopIcon($order->status),
            'online_qq' => '466813637',
            'can_delete' => $order->isCanDelete() ? 1 : 0,
            'status_id' => $order->status,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s', $order->created_at),
            'title' => '订单详情',
            'user_info' => [
                'user_name' => substr_replace($order->user_name, '*****', 3, 4),
            ],
            'products' => $products,
            'amount' => $order->amountDesc ?: ($this->isAndroid() ? [
                [
                    'name' => '',
                    'price' => '',
                ]
            ] : null),
//                [
//                    'name' => '-折扣',
//                    'price' => '￥10',
//                ],
            'actions' => $order->actions($this->isAndroid(), $flag),
            'pay_type' => $order->getPayType(),
            'product_amount' => '￥' . floatval($order->product_amount),
            'pay_amount' => '￥' . floatval($order->pay_amount),
        ];

        self::showMsg($data);
    }

    /** 取消订单 */
    public function actionCancel()
    {

        $order = $this->findOrderModel(['sn' => Yii::$app->request->get('sn'), 'user_id' => $this->userId]);

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $oldStatus = $order->status;

            if (!$order->cancel()) {
                throw new Exception('取消订单失败');
            }

            if (!$order->setCouponEnable()) {
                throw new Exception('取消订单失败');
            }

            $orderLog = new OrderLog();
            $orderLog->userEntity = $this->getApiUser();

            if (!$orderLog->createLog($order, '由 ' . Order::orderStatus()[$oldStatus] . ' 改为 ' . $order->getStatus())) {
                throw new Exception('记录订单日志失败:' . current($orderLog->getFirstErrors()));
            }

            $transaction->commit();
            self::showMsg('取消订单成功', 1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
    }

    /** 删除订单 (伪删除) */
    public function actionDelete()
    {

        $order = $this->findOrderModel(['sn' => Yii::$app->request->get('sn'), 'user_id' => $this->userId]);

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $oldStatus = $order->status;

            if (!$order->delete()) {
                throw new Exception('订单不允许删除');
            }

            if (!$order->setCouponEnable()) {
                throw new Exception('订单不允许删除');
            }

            $orderLog = new OrderLog();
            $orderLog->userEntity = $this->getApiUser();

            if (!$orderLog->createLog($order, '由 ' . Order::orderStatus()[$oldStatus] . ' 改为 ' . $order->getStatus())) {
                throw new Exception('记录订单日志失败:' . current($orderLog->getFirstErrors()));
            }

            $transaction->commit();
            self::showMsg('订单已删除', 1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
    }

    /** 获取pay参数 */
    public function actionAlipayWeb()
    {
        echo '这是支付宝web支付接口';
    }

    /** 获取pay参数 */
    public function actionPay()
    {
        $order = $this->findOrderModel(['sn' => Yii::$app->request->get('sn'), 'user_id' => $this->userId]);

        if (Yii::$app->request->get('pay_type')) {
            $order->setPayType(Yii::$app->request->get('pay_type'));
        }

        try {
            $this->pay = array_merge($this->pay, $order->getPayParams());
        } catch (Exception $e) {
            self::showMsg($e->getMessage(), -1);
        }

        self::showMsg($this->pay);
    }

    /** 取订单实体 */
    protected function findOrderModel($params)
    {
        /**
         * 如果加这个条件, 客户端在删除订单操作完成后重新刷新了详情页面
         */
//        $params['deleted_at'] = 0;
        if (($model = Order::findOne($params)) !== null) {
            return $model;
        } else {
            self::showMsg('订单不存在', -1);
        }
    }
}
