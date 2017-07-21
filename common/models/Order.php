<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $buyer_id
 * @property integer $seller_id
 * @property string $sn
 * @property integer $pay_type
 * @property string $pay_amount
 * @property string $product_amount
 * @property string $discount_amount
 * @property string $ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Order extends Base
{
    public $productTags = []; // 订单商品的所有标签
    public $buyCount = []; // 购买总数
    public $productAttribute = []; // 产品sku
    public $products = []; // 产品
    public $productsAmount = 0.00; // 订单商品总金额
    public $discountAmount = 0.00; // 折扣总金额
    public $freight = 0.00; // 运费
    public $amountDesc = []; // 折扣明细,包括运费等
    public $payAmount = 0.00; // 折扣总金额
    public $coupons = []; // 优惠券

    const STATUS_WAIT_PAY = 10; // 待付款
    const STATUS_PAYED = 15; // 已付款
    const STATUS_WAIT_SHIP = 20; // 待发货
    const STATUS_SHIPPED = 25; // 已发货
//    const STATUS_CONFIRM_RECEIVING = 40; // 待签收
    const STATUS_CONFIRM_RECEIVING = 50; // 已签收
    const STATUS_RETURN_APPLY = 60; // 退款申请 (发货后, 买家不想要了)
    const STATUS_RETURN_AGREE = 61; // 卖家同意退款申请 (发货后, 买家不想要了)
    const STATUS_WAIT_REFUND = 65; // 待退款 (揭晓后, 给没中奖的客户退款,卖家同意退款)
    const STATUS_REFUNDED = 68; // 已退款 (退款成功)
    const STATUS_WAIT_COMMENT = 70; // 待评价
//    const STATUS_SELLER_COMMENTED = 71; // 卖家已评价
//    const STATUS_BUYER_COMMENTED = 72; // 买家已评价
//    const STATUS_ALL_COMMENTED = 73; // 买卖双方已评价
    const STATUS_COMPLETE = 100; // 已完成 买卖双方已评价后状态变为已完成

    const EVALUATION_STATUS_1 = 1; // 卖家已评价
    const EVALUATION_STATUS_2 = 2; // 买家已评价
    const EVALUATION_STATUS_3 = 3; // 双方已评价

    public static function orderStatus()
    {
        return [
            self::STATUS_WAIT_PAY => '待付款',
//            self::STATUS_WAIT_PAY_SUCCESS => '已付款',
            self::STATUS_WAIT_SHIP => '待发货',
            self::STATUS_CONFIRM_RECEIVING => '待签收',
            self::STATUS_RETURN_APPLY => '退货申请',
            self::STATUS_WAIT_COMMENT => '待评价',
            self::STATUS_COMPLETE => '已完成',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['freight'], 'default', 'value' => '0'],
            [['buyer_id', 'seller_id', 'sn', 'status', 'ip'], 'required'],
            [['buyer_id', 'seller_id', 'evaluation_status', 'pay_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pay_amount', 'product_amount', 'discount_amount'], 'number'],
            [['sn', 'ip'], 'string', 'max' => 100],
            [['user_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'buyer_id' => '买家',
            'seller_id' => '卖家',
            'sn' => '订单号',
            'pay_type' => '支付类型',
            'pay_amount' => '支付金额',
            'product_amount' => '产品金额',
            'discount_amount' => '优惠后的金额',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'evaluation_status' => '评价状态,卖家已评价=1,买家已评价=2,双方已评价=3',
        ];
    }

    /**
     * Name: confirmPrice
     * Desc: 计算需支付的金额
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-07
     */
    public function confirmPrice()
    {
        foreach ($this->products as $key => $product) {
            $this->productsAmount += $product['model']->getPrice($product['buy_type']) * $product['count'];
        }

        $this->freight = $this->products[0]['model']->freight;

        $this->confirmUserCoupon();

        $this->amountDesc = [
            [
                'title' => '商品合计',
                'price' => '+ ￥' . floatval($this->productsAmount),
            ],
            [
                'title' => '运费',
                'price' => '+ ￥' . $this->products[0]['model']->freight,
            ],
        ];

        foreach ($this->coupons as $coupon) {

            // 优惠券
            $this->amountDesc[] = [
                'title' => $coupon->title,
                'price' => '- ￥' . $coupon->price,
            ];

            $this->discountAmount += $coupon->price;
        }

        if ($this->discountAmount > 0) {
            $this->amountDesc[] = [
                'title' => '红包抵扣',
                'price' => '- ￥' . floatval($this->discountAmount),
            ];
        }

        $this->payAmount = floatval(number_format($this->productsAmount > $this->discountAmount
            ? $this->productsAmount + $this->freight - $this->discountAmount : $this->discountAmount, 2, '.', ''));

        $this->amountDesc[] = [
            'title' => '支付金额',
            'price' => '￥' . $this->payAmount,
        ];
    }

    public $canUseCouponTags = []; // 用户可以使用的优惠券(可以用的优惠券Tags)

    /** 获取用户可用优惠券 */
    public function confirmUserCoupon()
    {
        $userCoupons = UserCoupon::find()->where(['user_id' => Yii::$app->user->identity->id, 'status' => UserCoupon::STATUS_UNUSED])->all();

        $getCanUseCouponIds = ArrayHelper::getColumn($userCoupons, 'coupon_id');

        $canUseCouponTags = TagCoupon::find()->where(['coupon_id' => $getCanUseCouponIds])->all();

        foreach ($canUseCouponTags as $canUseCouponTag) {
            $this->canUseCouponTags[$canUseCouponTag->coupon_id][] = $canUseCouponTag->tid;
        }

        if ($this->canUseCouponTags && ($productTags = $this->getProductCoupon())) {
            foreach ($this->canUseCouponTags as $couponId => $tagIds) {
                if (!empty($productTags[$couponId])) {
                    foreach ($tagIds as $id => $tagId) {
                        if (in_array($tagId, $productTags[$couponId])) {
                            // 券可以使用
                            $couponIds[] = $couponId;
                            break; // 只查一个即可
                        }
                    }
                }
            }

            if (!empty($couponIds)) {
                $query = Coupon::find()->where(['id' => $couponIds]);
                $query->andWhere(['<', 'price', $this->productsAmount]);
                $this->coupons = $query->offset(0)->limit(1)->orderBy('price desc')->all(); // 限制使用一张券
            }
        }
    }

    /** 判断商品使用这些优惠券 可以折扣的费用 */
    public function getProductCoupon()
    {
        $productTags = $productTagCoupons = [];

        if ($this->products) {
            foreach ($this->products as $key => $product) {
                if ($product->tag) {
//                    print_r(ArrayHelper::getColumn($product->tag, 'tid'));
                    $productTags = array_merge($this->productTags, ArrayHelper::getColumn($product->tag, 'tid'));
                }
            }
        }

        if ($productTags) {
            $productTagCoupons = TagCoupon::find()->where(['tid' => $productTags])->all();

            $productTagCoupons = ArrayHelper::map($productTagCoupons, 'id', 'tid', 'coupon_id');
        }

        return $productTagCoupons;
    }

    /** 创建订单商品 */
    public function saveProducts($newOrder)
    {
        foreach ($newOrder->products as $key => $product) {
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $newOrder->id;
            $orderProduct->title = $product['model']->title;
            $orderProduct->pid = $product['model']->id;
            $orderProduct->count = $product['count'];
            $orderProduct->price = $product['model']->price;
            $orderProduct->discount_price = 0.00;
            $orderProduct->coupon_id = 0;

            if (!$orderProduct->save()) {
                throw new Exception('创建订单商品失败');
            }
        }
    }

    /** 随机生产sn */
    public function createOrderSn()
    {
        for ($i = 0; $i < 5; $i++) {
            $x = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            if (Order::find()->where(['sn' => $x])->count()) {
                continue;
            }
            return $x;
        }
        return '';
    }

    /** 创建订单 */
    public function create()
    {
        $this->ip = Yii::$app->request->userIP;
        $this->sn = $this->createOrderSn();
        $this->buyer_id = Yii::$app->user->identity->id;
        $this->seller_id = $this->products[0]['model']->created_by;
        $this->status = self::STATUS_WAIT_PAY;
        $this->pay_amount = $this->payAmount;
        $this->product_amount = $this->productsAmount;
        $this->discount_amount = $this->discountAmount;
        $this->freight = $this->freight;
        $this->pay_type = 0;

        if (!$this->save()) {
//            print_r($this->getErrors());exit;
            throw new Exception('创建订单失败');
        }

        return $this;
    }

    /** 生成摇奖号码 次方法仅支持一个订单一个宝贝的情况*/
    public function createAwardCode()
    {
        $r = [0, ''];

        if ($this->orderProduct && $this->orderProduct->buy_type == OrderProduct::UNIT_PRICE) {
            // 购买方式是参与众筹,在支付成功后,生成摇奖编码
            $maxAwardCode = $this->orderProduct->product->getMaxAwardCode(); // 最大编码
            $newAwardCode = $this->orderProduct->product->getMaxAwardCode() + $this->orderProduct->count; // 新增后的最大
            for ($newAwardCode; $newAwardCode > $maxAwardCode; $newAwardCode--) {
                // 生成摇奖编号
                $awardCodeModel = new OrderAwardCode();
                $awardCodeModel->setAttributes([
                    'order_id' => $this->id,
                    'order_product_id' => $this->orderProduct->id,
                    'product_id' => $this->orderProduct->product_id,
                    'code' => $newAwardCode,
                    'seller_id' => $this->seller_id,
                    'buyer_id' => $this->buyer_id,
                ]);
                if (!$awardCodeModel->save()) {
                    return [400, '保存摇奖号码失败'];
                }
            }

            if ($this->orderProduct->product && $this->orderProduct->product->model == Product::MODEL_NUMBER) {
                // 数量模式的参与方式需要 更新宝贝数据,包括参与人数
                if ($this->orderProduct->buy_type == OrderProduct::UNIT_PRICE) {
                    // 单价购买意味着是 众筹模式
                    $this->orderProduct->product->order_award_count += 1;

                    if ($this->orderProduct->product->order_award_count >= $this->orderProduct->product->order_award_count // 已参与人数达到需要参与人数
                        && $this->orderProduct->product->model == Product::MODEL_NUMBER // 数量模式
                    ) {
                        $this->orderProduct->product->progress = $this->orderProduct->product->getNewProgress($this->orderProduct->product->order_award_count);
                        $this->orderProduct->product->status = Product::STATUS_WAIT_PUBLISH; // 待揭晓
                        $this->orderProduct->product->count_down = time() + 86400; // 揭晓倒计时截止时间戳
                    }
                    if (!$this->orderProduct->product->save()) {
                        return [400, '更新宝贝参与记录失败'];
                    }
                } else {
                    // 一口价 购买
                }
            }
        }

        return $r;
    }

    /** 获取买家当前订单的摇奖号码 */
    public function getAwardCodesByOrder()
    {
        $query = OrderAwardCode::find();
        $query->where([
            'order_id' => $this->id
        ]);
        return ArrayHelper::getColumn($query->asArray()->all(), 'code');
    }

    /** 获取买家所参与宝贝的摇奖号码 */
    public function getAwardCodesByUser()
    {
        $query = OrderAwardCode::find();
        $query->where([
            'user_id' => $this->buyer_id,
            'product_id' => $this->orderProduct->product_id
        ]);
        return ArrayHelper::getColumn($query->asArray()->all(), 'code');
    }

    /** 修改优惠券已被使用 */
    public function saveCoupon($newOrder)
    {
        foreach ($this->coupons as $coupon) {
            $userCoupon = UserCoupon::findOne(['user_id' => $newOrder->buyer_id, 'coupon_id' => $coupon->id]);
            if ($userCoupon) {
                $userCoupon->order_id = $newOrder->id;
                $userCoupon->status = UserCoupon::STATUS_USED;
                $userCoupon->used_at = time();
                if (!$userCoupon->save()) {
                    throw new Exception('提交订单失败');
                }
            }

        }
    }

    /** 设置支付方式 */
    public function setPayType($pay_type)
    {
        $type = array_search($pay_type, BaseData::$payType);
        $this->pay_type = $type ?: '';

        return $this;
    }

    /** 获取支付方式 */
    public function getPayTypes($payIds)
    {
        $payIds = @json_decode($payIds, true);

        $return = [];

        if (is_array($payIds)) {

            if (in_array('微信支付', $payIds)) {
                $return[] = [
                    'show' => 1,
                    'detail' => '推荐开通微信支付的用户',
                    'type' => BaseData::$payType[2],
                    'img' => Url::to('/images/pay_weixin.png', 1)
                ];
            }

            if (in_array('支付宝支付', $payIds)) {
                $return[] = [
                    'show' => 1,
                    'detail' => '推荐有支付宝的用户使用',
                    'type' => BaseData::$payType[1],
                    'img' => Yii::$app->params['qiniu_url_images'] . 'pay_alipay1.png'
                ];
            }
        }

        return $return;
    }

    /** 获取支付参数 */
    public function getPayParams($payType = '')
    {
        $payType = $payType ?: $this->pay_type;
        switch ($payType) {
            case 1 :  // 支付宝
                return $this->_alipay();
                break;

            case 2: // 微信
                return $this->_weixinPay();
                break;
        }
    }

    /** 获取支付宝支付参数 */
    public function _alipay()
    {
        require_once(Yii::getAlias('@app') . "/sdk/alipay/alipay.config.php");
        require_once(Yii::getAlias('@app') . "/sdk/alipay/lib/alipay_core.function.php");
        require_once(Yii::getAlias('@app') . "/sdk/alipay/lib/alipay_rsa.function.php");

        $productTitle = $this->product ? ArrayHelper::getValue($this->product, "0.title") : '';

        $apiParams = [
            'service' => 'mobile.securitypay.pay',
            'out_trade_no' => $this->sn,
            '_input_charset' => 'utf-8',
            'total_fee' => $this->pay_amount,
            'subject' => $productTitle,
            'body' => $productTitle,
            'partner' => Yii::$app->params['alipay_partner'],
            'notify_url' => Url::to('/alipayCallback.php', true),
            'payment_type' => 1,
            'goods_type' => 0, // 0 虚拟 1 实物
            'seller_id' => '1@diyixue.com',
        ];
//        Helper::writeLog($apiParams);
        $apiParams = $apiParams2 = argSort($apiParams);

        foreach ($apiParams as $key => $val) {
            $apiParams[$key] = '"' . $val . '"';
        }

        $apiParams = createLinkstring($apiParams);

        $signData = rsaSign($apiParams, Yii::getAlias('@app') . "/sdk/alipay/key/rsa_private_key.pem");

        return ['alipay' => $apiParams . '&sign_type="RSA"&sign="' . urlencode($signData) . '"'];
    }

    /** 获取微信支付参数 */
    public function _weixinPay()
    {
        $productTitle = $this->product ? ArrayHelper::getValue($this->product, "0.title") : '';

        if ($this->pay) {
            $signParams = [
                'appid' => Yii::$app->params['wx_app_id'],
                'partnerid' => Yii::$app->params['wx_mch_id'],
                'prepayid' => $this->pay->out_trade_no,
                'noncestr' => md5($this->sn . time() . mt_rand(1, 9999999)),
                'timestamp' => (string)time(),
                'package' => "Sign=WXPay",
            ];

            // 如果已经有微信支付日志, 直接返回
            return [
                "wx_appid" => (string)Yii::$app->params['wx_app_id'],
                "wx_sign" => Weixin::getSign($signParams),
                "wx_timestamp" => (string)time(),
                "wx_partner_id" => Yii::$app->params['wx_mch_id'],
                "wx_package" => "Sign=WXPay",
                "wx_nonce_str" => $signParams['noncestr'],
                "wx_prepay_id" => $signParams['prepayid'],
            ];
        }

        $apiParams = [
            'appid' => Yii::$app->params['wx_app_id'],
            'mch_id' => Yii::$app->params['wx_mch_id'],
            'nonce_str' => md5(mt_rand(1, 999999999) . $this->sn),
            'body' => $productTitle,
            'out_trade_no' => $this->sn,
            'total_fee' => $this->pay_amount * 100,
            'spbill_create_ip' => Helper::getIP(),
            'notify_url' => Url::to('/weixinpayCallback.php', true),
            'trade_type' => 'APP',
        ];

        $apiParams['sign'] = Weixin::getSign($apiParams);

        $xml = '<xml>';
        foreach ($apiParams as $key => $val) {
            $xml .= "<$key>$val</$key>";
        }
        $xml .= '</xml>';

        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

        $response = Helper::post($url, $xml);

        list($data, $ok) = Weixin::checkSign($response);

        if ($ok == false) {
            throw new Exception('签名验证失败', -1);
        }

        $xml = simplexml_load_string($response);

        if (empty($data['return_code'])) {
            throw new Exception('创建微信预支付订单失败');
        }

        if ($data['return_code'] == 'FAIL') {
            throw new Exception(empty($data['return_msg']) ? '创建微信预支付订单失败' : $data['return_msg']);
        }

        if ($data['result_code'] == 'FAIL') {
            throw new Exception(empty($data['err_code_des']) ? '创建微信预支付订单失败' : $data['err_code_des']);
        }

        if ($data['return_code'] == 'SUCCESS') {
            if (empty($data['prepay_id'])) {
                throw new Exception((empty($data['return_msg']) ? '创建微信预支付订单失败' : $data['return_msg']));
            }

            /** 保存微信支付日志 */
            $pay = new OrderPay();
            $pay->sn = $this->sn;
            $pay->order_id = $this->id;
            $pay->pay_type = $this->pay_type;
            $pay->out_trade_no = $data['prepay_id'];
            $pay->out_trade_status = '等待付款';
            $pay->log = $response;
            $pay->status = '1';

            if (!$pay->save()) {
                print_r($pay->getErrors());
                throw new Exception('创建订单支付日志失败');
            }

            $sign = Weixin::getSign([
                'appid' => Yii::$app->params['wx_app_id'],
                'partnerid' => Yii::$app->params['wx_mch_id'],
                'partnerid' => $data['mch_id'],
                'prepayid' => $data['prepay_id'],
                'noncestr' => $data['nonce_str'],
                'timestamp' => (string)time(),
                'package' => "Sign=WXPay",
            ]);

            return [
                "wx_appid" => (string)Yii::$app->params['wx_app_id'],
                "wx_sign" => $sign,
                "wx_timestamp" => (string)time(),
                "wx_partner_id" => $data['mch_id'],
                "wx_package" => "Sign=WXPay",
                "wx_nonce_str" => $data['nonce_str'],
                "wx_prepay_id" => $data['prepay_id'],
            ];
        }

        throw new Exception('微信支付暂时不可用');
    }

    /** 收银台接口所需数据 */
    public function checkout()
    {
        $r = [
            'sn' => $this->sn,
            'default_pay_type' => '支付宝支付',
            'pay_types' => $this->getPayTypes(Yii::$app->request->post('pay_ids', '["支付宝支付","微信支付"]')),
            'amount' => [
                [
                    'title' => '商品合计',
                    'price' => '+ ￥' . floatval($this->product_amount),
                ],
                [
                    'title' => '运费',
                    'price' => '+ ￥' . floatval($this->freight),
                ],
            ],
        ];

        if ($this->discountAmount > 0) {
            $r['amount'][] = [
                'title' => '红包抵扣',
                'price' => '- ￥' . floatval($this->discount_amount),
            ];
        }

        $r['amount'][] = [
            'title' => '支付金额',
            'price' => '￥' . floatval($this->pay_amount),
        ];

        return $r;
    }

    /** 取买家信息 */
    public function getSeller()
    {
        return $this->hasOne(User::className(), ['id' => 'seller_id']);
    }

    /** 取卖家信息 */
    public function getBuyer()
    {
        return $this->hasOne(User::className(), ['id' => 'buyer_id']);
    }

    /** 取订单商品一条 */
    public function getOrderProduct()
    {
        return $this->hasOne(OrderProduct::className(), ['order_id' => 'id']);
    }

    /** 是否为一口价订单 */
    public function isAPriceOrder()
    {
        return $this->orderProduct->buy_type == OrderProduct::A_PRICE;
    }

    /** 卖家-待发货的订单 */
    public function sellerWaitShipping()
    {

    }

    /** 卖家-待签收的宝贝 (买家退货)*/
    public function sellerWaitReceiving()
    {

    }

    /** 卖家-待评价的宝贝 */
    public function sellerWaitAssessment()
    {

    }

    /** 卖家-已完成的宝贝*/
    public function sellerCompleted()
    {

    }

    /** 卖家-买家的退货申请 */
    public function sellerSalesReturn()
    {

    }

    // ---------------------------------------卖家/买家分割线-------------------------------------- //


    /** 待付款的订单 */
    public function waitPay()
    {

    }

    /** 已下架的宝贝 */
    public function cancel()
    {

    }

    /** 用户对宝贝可以进行的操作,编辑删除等 */
    public function userActions()
    {

    }

    /** 卖家-待评价的宝贝 */
    public function buyerWaitAssessment()
    {

    }

    /** 卖家-已完成的宝贝*/
    public function buyerCompleted()
    {

    }
}
