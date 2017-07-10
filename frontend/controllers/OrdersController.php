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
     * @SWG\Get(path="/orders/confirm",
     *   tags={"订单"},
     *   summary="提交订单前的确认",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(
     *     name="products",
     *     in="query",
     *     default="[{'id':'1234','count':'1','buy_type':'a_price || unit_price'}, {'id':'231','count':'1','buy_type':'a_price || unit_price'}]",
     *     description="提交订单前的确认",
     *     required=false,
     *     type="string",
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
            list($err, $msg) = $item['product']->canbuy();
            if ($err) {
                self::showMsg($msg, -1);
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

        self::showMsg($data);
    }

    /**
     * Name: actionSubmit
     * Desc: 提交订单
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-07
     * @SWG\Get(path="/orders/submit",
     *   tags={"订单"},
     *   summary="提交订单",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(
     *     name="products",
     *     in="query",
     *     default="[{'id':'1234','count':'1','buy_type':'a_price || unit_price'}, {'id':'231','count':'1','buy_type':'a_price || unit_price'}]",
     *     description="购买的宝贝明细",
     *     required=false,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="pay_type",
     *     in="query",
     *     default="alipay",
     *     description="使用的支付方式",
     *     required=false,
     *     type="string",
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
//            $pay = array_merge($this->pay, $newOrder->getPayParams());
//            $pay['sn'] = $newOrder->sn;

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
//            print_r($e->getMessage());
//            exit;
            self::showMsg($e->getMessage(), -1);
        }

        $pay = [
            'sn' => $newOrder->sn,
            'default_pay_type' => '支付宝支付',
//            'pay_amount' => '￥' . floatval($order->pay_amount),
//            'product_amount' => '￥' . floatval($order->product_amount),
            'pay_types' => $order->getPayTypes(Yii::$app->request->post('pay_ids', '["支付宝支付","银行柜台转账"]')),
            'amount' => [
                [
                    'name' => '商品合计',
                    'price' => '￥' . floatval($order->product_amount),
                ],
            ],
        ];

        if ($this->discountAmount > 0) {
            $pay['amount'][] = [
                'name' => '红包抵扣',
                'price' => '-￥' . floatval($this->discountAmount),
            ];
        }
        $pay['amount'][] = [
            'name' => '支付金额',
            'price' => '￥' . floatval($order->pay_amount),
        ];

        self::showMsg($pay);
    }

    /** 订单支付成功 */
    public function actionPaymentSuccess()
    {
        $order = $this->findOrderModel(['sn' => Yii::$app->request->get('sn'), 'user_id' => $this->userId]);

        $params = [];

        // 下放摇奖编号
        $order->createAwardNumber();

        $data = [
            'id' => $order->id,
            'pay_amount' => '￥' . floatval($order->pay_amount),
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

    /**
     * Name: actionGetPayParams
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-08
     * @SWG\Get(path="/orders/get-pay-params",
     *   tags={"订单"},
     *   summary="获取支付参数",
     *   description="Author: lixinxin 这个是在收银台接口之后调用,客户端获取到支付参数后,再请求支付宝或微信客户端",
     *   @SWG\Parameter(
     *     name="sn",
     *     in="query",
     *     default="20170101",
     *     description="订单号",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="pay_type",
     *     in="query",
     *     default="支付宝支付",
     *     description="支付方式",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionGetPayParams()
    {
        $order = $this->findOrderModel(['sn' => Yii::$app->request->get('sn'), 'user_id' => $this->userId]);

        $payType = Yii::$app->request->get('pay_type');

        $this->pay = array_merge($this->pay, $order->getPayParams($payType));

        self::showMsg($this->pay);
    }

    /**
     * Name: actionCheckout
     * Desc: 收银台页面
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-08
     * @SWG\Get(path="/orders/checkout",
     *   tags={"订单"},
     *   summary="收银台接口",
     *   description="Author: lixinxin 在这个接口获取可以选择的支付方式, 选取之后紧接着调用orders/getPayParams 接口获取选择的支付方式的加密数据",
     *   @SWG\Parameter(
     *     name="sn",
     *     in="query",
     *     default="201708029320",
     *     description="订单号",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public  function actionCheckout()
    {
        $order = $this->findOrderModel(['sn' => Yii::$app->request->get('sn'), 'user_id' => $this->userId]);

        $data = [
            'sn' => $order->sn,
            'default_pay_type' => '支付宝支付',
            'pay_types' => $order->getPayTypes(Yii::$app->request->post('pay_ids', '["支付宝支付","微信支付"]')),
            'amount' => [
                [
                    'name' => '商品合计',
                    'price' => '￥' . floatval($order->product_amount),
                ],
            ],
        ];

        if ($this->discountAmount > 0) {
            $data['amount'][] = [
                'name' => '红包抵扣',
                'price' => '-￥' . floatval($this->discountAmount),
            ];
        }

        $data['amount'][] = [
            'name' => '支付金额',
            'price' => '￥' . floatval($order->pay_amount),
        ];

        self::showMsg($data);
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

    /**
     * Name: actionPaySuccess
     * Desc: 支付成功
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-00-00
     * @SWG\Get(path="/demo/demo",
     *   tags={"demo"},
     *   summary="",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(
     *     name="sn", in="query", required=true, type="integer", default="1",
     *     description="订单号"
     *   ),
     *   @SWG\Response(
     *       response=200,description="[{'msg':'你成功参与了1件宝贝共计2人次,活动编号如下', 'award_code':{'123','234'}}]"
     *   )
     * )
     */
    public function actionPaySuccess($sn)
    {
        $order = $this->findOrderModel(['sn' => $sn, 'user_id' => $this->userId]);
        $codes = $order->getAwardCodes();

        $data = [
            'msg' => '你成功参与了1件宝贝共计2人次,活动编号如下',
            'codes' => ''
        ];
        foreach ($codes as $code) {
            $data['codes'][] = $code;
        }

        self::showMsg($data);
    }
}
