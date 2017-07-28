<?php

/**
 * @link http://www.fangdazhongxin.com/
 * @copyright 2016 众筹夺宝
 * @author ulee@fangdazhongxin.com
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
        if (empty($this->userId) || empty(Yii::$app->user->identity)) {
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

    /**
     * Name: actionSellerProductList
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-11
     * @SWG\Get(path="/orders/seller-product-list",
     *   tags={"我的"},
     *   summary="我卖出的",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="status", in="query", required=true, type="integer", default="0",
     *     description="status的值分为: 全部=0 || 待发货=20 || 已发货=25 || 待评价=70 || 已完成=100 || 退款申请=60"
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
     *              layout=布局类型[
     *                  一口价购买 || 众筹购买
     *              ]
     *              product_id=宝贝id
     *              order_sn=订单号
     *              created_at=参与时间
     *              title=标题
     *              img=宝贝头图
     *              total=总需人次
     *              residual_total=剩余人次
     *              residual_time=结束时间
     *              progress=进度
     *              publish_countdown=揭晓倒计时
     *              a_price=一口价
     *              unit_price=单价
     *              status=状态 [待发货 || 已发货 || 待评价 || 已完成]
     *              actions=数组下是字典
     *                  [
     *                      title=评价
     *                      url=evaluation_add
     *                  ],
     *                  [
     *                      title=查看评价
     *                      url=evaluation_list
     *                  ],
     *              url=链接地址[跳转到宝贝详情页=product, 跳转到订单详情页=order]
     *              order_award_count=已参与人次"
     *   )
     * )
     */
    public function actionSellerProductList($status)
    {
        $orderModel = new Order();
        $orderModel->params = [
            'seller_id' => $this->userId,
            'offset' => Yii::$app->request->get('offset', 0),
            'status' => $status ?: [
                Order::STATUS_WAIT_SHIP, // 待发货
                Order::STATUS_SHIPPED, // 已发货
                Order::STATUS_CONFIRM_RECEIVING, // 已签收
                Order::STATUS_REFUND_APPLY, // 退款申请
                Order::STATUS_REFUND_AGREE, // 卖家同意退款申请
                Order::STATUS_REFUNDED, // 已退款
                Order::STATUS_WAIT_COMMENT, // 待评价
                Order::STATUS_COMPLETE, // 已完成
            ]
        ];

        if (in_array($status, [Order::STATUS_REFUND_APPLY, Order::STATUS_REFUND_AGREE, Order::STATUS_REFUNDED])) {
            //退款申请 同意退款 已退款, 这三种状态要同时查询
            $orderModel->params['status'] = [Order::STATUS_REFUND_APPLY, Order::STATUS_REFUND_AGREE, Order::STATUS_REFUNDED];
        }

        list($products, $count) = $orderModel->sellerOrders();

        $data = [
            'product_count' => $count,
            'product_list' => $products
        ];

        self::showMsg($data);
    }

    /**
     * Name: actionBuyerProductList
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-11
     * @SWG\Get(path="/orders/buyer-product-list",
     *   tags={"我的"},
     *   summary="我买到的",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="status", in="query", required=true, type="integer", default="0",
     *     description="status的值分为: 全部=0 || 待发货=20 || 待签收=25 || 待评价=70 || 已完成=100 || 退款申请=60"
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
     *              layout=布局类型[
     *                  一口价购买 || 众筹购买
     *              ]
     *              product_id=宝贝id
     *              order_sn=订单号
     *              created_at=参与时间
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
        $orderModel = new Order();
        $orderModel->params = [
            'buyer_id' => $this->userId,
            'offset' => Yii::$app->request->get('offset', 0),
            'status' => $status ?: [
                Order::STATUS_WAIT_SHIP, // 待发货
                Order::STATUS_SHIPPED, // 已发货
                Order::STATUS_CONFIRM_RECEIVING, // 已签收
                Order::STATUS_REFUND_APPLY, // 退款申请
                Order::STATUS_REFUND_AGREE, // 卖家同意退款申请
                Order::STATUS_REFUNDED, // 已退款
                Order::STATUS_WAIT_COMMENT, // 待评价
                Order::STATUS_COMPLETE, // 已完成
            ]
        ];

        if (in_array($status, [Order::STATUS_REFUND_APPLY, Order::STATUS_REFUND_AGREE, Order::STATUS_REFUNDED])) {
            //退款申请 同意退款 已退款, 这三种状态要同时查询
            $orderModel->params['status'] = [Order::STATUS_REFUND_APPLY, Order::STATUS_REFUND_AGREE, Order::STATUS_REFUNDED];
        }

        list($products, $count) = $orderModel->buyerOrders();

        $data = [
            'product_count' => $count,
            'product_list' => $products
        ];

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
                'price' => '¥' . floatval($op->price),
                'count' => $op->count
            ];
        }

        if ($order->userCoupon && $order->userCoupon->coupon) {
            $order->amountDesc[] = [
                'name' => $order->userCoupon->coupon->title,
                'price' => '- ¥' . $order->userCoupon->coupon->price,
            ];
        }
        $data = [
            'title' => '支付',
            'user_name' => substr_replace(ArrayHelper::getValue($order->userEntity, 'username'), '****', 3, 4),
            'pay_amount' => '¥' . floatval($order->pay_amount),
            'product_amount' => '¥' . floatval($order->product_amount),
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

        if (empty($products)) {
            self::showMsg('数据格式错误', -1);
        }

        $r = [];

        foreach ($products as $product) {
            if (!empty($product['id'])) {
                $productModel = Product::find()->where(['id' => $product['id']])->one();
                if ($productModel) {
                    if ($productModel->created_by == $this->userId) {
                        self::showMsg('不允许购买自己的宝贝', -1);
                    }
                    if ($productModel->canBuy()) {
                        // 判断是否可以购买
                        $r[] = [
                            'model' => $productModel,
                            'count' => $product['count'],
                            'buy_type' => $product['buy_type'], // 购买方式 unit_price=众筹参与,a_price=一口价
                        ];
                    }
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
     * @SWG\Post(path="/orders/confirm",
     *   tags={"订单"},
     *   summary="提交订单前的确认",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="products", in="formData", required=false, type="string", default="[{'id':1,'count':1,'buy_type':1}]",
     *     description="提交订单前的确认 buy_type:1=一口价2=单价",
     *   ),
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *       response=200,description="
     *          title = 确认订单
     *          pay_amount = 实付款
     *          amount = 数组加字典
     *              [
     *                  title => 运费,
     *                  price => + ¥1212.12,
     *              [
     *          products = 宝贝列表
     *              [
     *                  id = 宝贝id
     *                  title = 宝贝标题
     *                  img = 宝贝头图
     *                  price = 宝贝价格
     *                  count = 购买数量
     *              ]
     *          address = 默认默认地址, 字典
     *              id = 1
     *              user_name = 收货人姓名
     *              postal = 邮编
     *              telephone = 手机号码
     *              str_address = 收货地址1
     *              detail_address = 收货地址2
     *              default_address = 是否为默认地址
     *     ",
     *   )
     * )
     */
    public function actionConfirm()
    {
        $order = new Order();

        // 检查宝贝
        $order->products = $this->checkProduct();

        $order->confirmPrice();

        $dataProducts = [];
        foreach ($order->products as $key => $item) {
            list($err, $msg) = $item['model']->canBuy();
            if ($err) {
                self::showMsg($msg, -1);
            }

            $dataProducts[] = [
                'id' => $item['model']->id,
                'title' => $item['model']->title,
                'img' => $item['model']->headImg(),
                'price' => '¥' . $item['model']->getPrice($item['buy_type']),
                'count' => $item['count']
            ];
        }

        if (empty($dataProducts)) {
            self::showMsg('宝贝不存在', -1);
        }

        $data = [
            'title' => '确认订单',
//            'user_name' => substr_replace(ArrayHelper::getValue(Yii::$app->user->identity, 'username'), '****', 3, 4),
            'pay_amount' => '¥' . floatval($order->payAmount),
//            'product_amount' => '¥' . floatval($order->productsAmount),
            'amount' => $order->amountDesc,
            'products' => $dataProducts,
            'address' => [
                'id' => 0,
                'user_name' => '',
                'postal' => '',
                'str_address' => '',
                'detail_address' => '',
                'telephone' => '',
                'default_address' => ''
            ]
        ];

        // 取默认收货人地址
        if ($address = UserAddress::getDefaultAddress()) {
            $data['address'] = [
                'id' => $address->id,
                'user_name' => $address->user_name,
                'postal' => $address->postal,
                'str_address' => $address->str_address,
                'detail_address' => $address->detail_address,
                'telephone' => $address->telephone,
                'default_address' => $address->default_address

            ];
        }

        self::showMsg($data);
    }

    /**
     * Name: actionSubmit
     * Desc: 提交订单
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-07
     * @SWG\Post(path="/orders/submit",
     *   tags={"订单"},
     *   summary="提交订单",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="products", in="formData", required=true, type="string", default="[{'id':1,'count':1,'buy_type':1}]",
     *     description="购买的宝贝明细 buy_type:1=一口价2=单价",
     *   ),
     *   @SWG\Parameter(name="address_id", in="formData", required=false, type="string", default="1",
     *     description="收货人地址id 当购买的方式为一口价时 为必填",
     *   ),
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *       response=200,description="
     *          sn=订单号
     *          default_pay_type=默认支付方式 支付宝支付
     *          pay_types=默认支付方式 支付宝支付, 数组加字典
     *              [
     *                  show=是否启用,1=启用,0=不启用
     *                  detail=支付方式介绍,例如: 推荐开通微信支付的用户
     *                  type=支付方式中文名称,例如:支付宝
     *                  img=支付方式icon图标
     *              ]
     *          amount=金额明细,数组加字典
     *              [
     *                  title=商品合计
     *                  price=+ ¥10.22
     *              ]
     *
     *     ",
     *   )
     * )
     */
    public function actionSubmit()
    {
        // 检查宝贝
        $order = new Order();

        $order->products = $this->checkProduct();

        Yii::$app->user->identity = $order->userEntity = $this->getApiUser();

//        $order->setPayType(Yii::$app->request->post('pay_type'));

        $order->confirmPrice();

        $dataProducts = [];
        foreach ($order->products as $key => $item) {
            if ($item['model']->canbuy() == false) {
                self::showMsg('活动已结束, 不允许购买', -1);
            }

            $dataProducts[] = [
                'id' => $item['model']->id,
                'title' => $item['model']->title,
                'img' => $item['model']->headImg(),
                'price' => '¥' . $item['model']->getPrice($item['buy_type']),
                'count' => $item['count']
            ];
        }

        if (empty($dataProducts)) {
            self::showMsg('产品不存在', -1);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $order->setAddress(Yii::$app->request->post('address_id'));
            $newOrder = $order->create();

            $order->saveProducts($newOrder);
            $order->saveCoupon($newOrder);

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }

        self::showMsg($newOrder->checkout());
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
     *   @SWG\Parameter(name="sn", in="query", required=true, type="string",
     *     default="20170101",
     *     description="订单号",
     *   ),
     *   @SWG\Parameter(name="pay_type", in="query", required=true, type="string",
     *     default="支付宝支付",
     *     description="支付方式",
     *   ),
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *       response=200,description="
     *          alipay
     *          wx_sign
     *          wx_timestamp
     *          wx_partner_id
     *          wx_package
     *          wx_nonce_str
     *          yl_tn
     *          wx_prepay_id
     *     "
     *   )
     * )
     */

    public function actionGetPayParams()
    {
        $order = $this->findOrderModel(['sn' => Yii::$app->request->get('sn'), 'buyer_id' => $this->userId]);

        $payType = Yii::$app->request->get('pay_type');
        try {
            $this->pay = array_merge($this->pay, $order->getPayParams($payType));
            self::showMsg($this->pay);
        } catch (Exception $e) {
            self::showMsg($e->getMessage(), -1);
        }


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
     *   @SWG\Parameter(name="sn", in="query", required=true, type="string", default="201708029320",
     *     description="订单号",
     *   ),
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *       response=200,description="
     *          sn=订单号
     *          default_pay_type=默认支付方式 支付宝支付
     *          pay_types=默认支付方式 支付宝支付, 数组加字典
     *              [
     *                  show=是否启用,1=启用,0=不启用
     *                  detail=支付方式介绍,例如: 推荐开通微信支付的用户
     *                  type=支付方式中文名称,例如:支付宝
     *                  img=支付方式icon图标
     *              ]
     *          amount=金额明细,数组加字典
     *              [
     *                  title=商品合计
     *                  price=+ ¥10.22
     *              ]
     *      "
     *   )
     * )
     */
    public function actionCheckout()
    {
        $order = $this->findOrderModel(['sn' => Yii::$app->request->get('sn'), 'user_id' => $this->userId]);

        $data = $order->checkout();

        self::showMsg($data);
    }

    /**
     * Name: actionPaymentSuccess
     * Desc: 订单支付结果确认
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-00-00
     * @SWG\Get(path="/demo/demo",
     *   tags={"订单"},
     *   summary="订单支付成功, 客户端回调接口",
     *   description="若用户取消支付或支付失败,则跳转到我参与的列表页面",
     *   @SWG\Parameter(name="id", in="query", required=true, type="integer", default="1",
     *     description=""
     *   ),
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *       response=200,description="
     *          id=1
     *          name=测试"
     *   )
     * )
     */
    public function actionPaymentSuccess()
    {
        $order = $this->findOrderModel(['sn' => Yii::$app->request->get('sn'), 'user_id' => $this->userId]);

        $params = [];

        // 下放摇奖编号
        $order->getAwardCodesByOrder();

        $data = [
            'layout' => $order->isAPriceOrder() ? 1 : 2,
            'msg' => '您成功参与了1件宝贝共计2人次,摇奖编号如下:',
            'pay_amount' => '¥' . floatval($order->pay_amount),
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

            if (!$orderLog->createLog($order, '由 ' . Order::$status[$oldStatus] . ' 改为 ' . $order->getStatus())) {
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

            if (!$orderLog->createLog($order, '由 ' . Order::$status[$oldStatus] . ' 改为 ' . $order->getStatus())) {
                throw new Exception('记录订单日志失败:' . current($orderLog->getFirstErrors()));
            }

            $transaction->commit();
            self::showMsg('订单已删除', 1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
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
     * Name: actionView
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-10
     * @SWG\Get(path="/orders/seller-view",
     *   tags={"我的"},
     *   summary="我卖出的订单详情",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="sn", in="query", required=true, type="string", default="201707101223",
     *     description=""
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionSellerView($sn)
    {
        $order = $this->findOrderModel(['sn' => $sn, 'seller_id' => $this->userId]);

        $orderProduct = $order->orderProduct;

        if (!$orderProduct) {
            self::showMsg('获取订单宝贝出错', -1);
        }

        $data = [
            'sn' => $order->sn,
            'status_line' => $order->sellerStatusLine(),
            'buyer_address_info' => [
                'user_id' => $order->buyer_id,
                'username' => $order->buyerAddress ? $order->buyerAddress->user_name : '',
                'telephone' => $order->buyerAddress ? $order->buyerAddress->telephone : '',
                'address' => $order->buyerAddress ? $order->buyerAddress->str_address : '',
            ],
            'product_layout' => $order->sellerOrderLayout(), // '布局样式: 一口价 || 参与众筹'
            'product_list' => [
                [
                    'id' => $orderProduct->product_id,
                    'title' => $orderProduct->product ? $orderProduct->product->title : '',
                    'img' => $orderProduct->product ? $orderProduct->product->headImg() : '',
                    'order_award_count' => $orderProduct->product ? (int)$orderProduct->product->order_award_count : 0,
                    'a_price' => $orderProduct->product->a_price,
                    'unit_price' => $orderProduct->product->unit_price,
                ]
            ],
            'luck_user' => [
                'user_id' => $order->buyer_id,
                'username' => $order->buyer ? $order->buyer->getName() : '',
                'order_sn' => $order->sn,
                'pay_amount' => $order->getPayTypeText() . ': ' . $order->pay_amount,
            ],
            'share_params' => [
                'share_title' => '众筹夺宝',
                'share_contents' => '夺宝达人!',
                'share_link' => 'http://www.baidu.com',
                'share_img_url' => 'https://www.baidu.com/img/bd_logo1.png',
            ],
            'shipping_info' => [
                'shipping_company' => $order->shippingCompany(),
                'shipping_number' => $order->shipping_number,
                'shipping_logs' => Shipping::shippingLogs($order->shipping_company, $order->shipping_number),
            ],
            'return_shipping_info' => [
                'shipping_company' => $order->returnShippingCompany(),
                'shipping_number' => $order->return_shipping_number,
                'shipping_logs' => Shipping::shippingLogs($order->return_shipping_company, $order->return_shipping_number),
            ],
            'actions' => $order->sellerViewActions()
        ];

        self::showMsg($data);
    }

    /**
     * Name: actionView
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-10
     * @SWG\Get(path="/orders/buyer-view",
     *   tags={"我的"},
     *   summary="我买到的订单详情",
     *   description="Author: lixinxin",
     *   @SWG\Parameter(name="sn", in="query", required=true, type="string", default="201707101223",
     *     description=""
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionBuyerView($sn)
    {
        $order = $this->findOrderModel(['sn' => $sn, 'buyer_id' => $this->userId]);

        $data = $order->sellerView();

        self::showMsg($data);
    }

}
