<?php

namespace common\models;

use Yii;
use yii\web\User;

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
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Order extends Base
{
    public $productTags = []; // 订单商品的所有标签
    public $buyCount = []; // 购买总数
    public $productAttribute = []; // 产品sku
    public $productsAmount = 0.00; // 订单商品总金额
    public $discountAmount = 0.00; // 折扣总金额
    public $amountDesc = []; // 折扣明细,包括运费等
    public $payAmount = 0.00; // 折扣总金额
    public $coupons = []; // 优惠券

    const STATUS_WAIT_PAY = 10; // 待付款
    const STATUS_WAIT_PAY_SUCCESS = 20; // 已付款
    const STATUS_PAYED = 30; // 待发货
    const STATUS_COMPLETED = 40; // 待签收
    const STATUS_CANCEL = 50; // 待评价
    const STATUS_DELETED = 90; // 已完成

    public static function orderStatus()
    {
        return [
            self::STATUS_WAIT_PAY => '待付款',
            self::STATUS_WAIT_PAY_CONFIRM => '转账待审核',
            self::STATUS_PAYED => '交易成功',
            self::STATUS_COMPLETED => '完成',
            self::STATUS_CANCEL => '交易关闭',
            self::STATUS_DELETED => '已删除',
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
            [['buyer_id', 'seller_id', 'sn', 'pay_type', 'status', 'created_at', 'updated_at'], 'required'],
            [['buyer_id', 'seller_id', 'pay_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pay_amount', 'product_amount', 'discount_amount'], 'number'],
            [['sn'], 'string', 'max' => 100],
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
        ];
    }

    /**
     * Name: confirmPrice
     * Desc: 计算需支付的金额
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-07
     * @param $products
     */
    public function confirmPrice($products)
    {
        foreach ($products as $key => $product) {
            if (in_array($product['buy_type'], [OrderProduct::A_PRICE, OrderProduct::UNIT_PRICE])) { // 一口价 和 单价
                $this->productsAmount += $product['product']->$product['buy_type'] * $product['count'];
            }
        }

        $this->confirmUserCoupon();

        $this->amountDesc[] = [
            'name' => '运费',
            'price' => '+ ￥0.00',
        ];

        foreach ($this->coupons as $coupon) {

            // 优惠券
            $this->amountDesc[] = [
                'name' => $coupon->title,
                'price' => '- ￥' . $coupon->price,
            ];

            $this->discountAmount += $coupon->price;
        }

        $this->payAmount = floatval(number_format($this->productsAmount > $this->discountAmount
            ? $this->productsAmount - $this->discountAmount : $this->discountAmount, 2, '.', ''));
    }

    public $canUseCouponTags = []; // 用户可以使用的优惠券(可以用的优惠券Tags)

    /** 获取用户可用优惠券 */
    public function confirmUserCoupon()
    {
        $userCoupons = UserCoupon::find()->where(['user_id' => $this->userEntity->id, 'status' => UserCoupon::STATUS_UNUSED])->all();

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

    /** 创建订单商品 */
    public function saveProducts($newOrder)
    {
        foreach ($this->productAttribute as $productAttributeId => $productAttribute) {
            if ($productAttribute->product) {
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $newOrder->id;
                $orderProduct->title = $productAttribute->product->title;
                $orderProduct->pid = $productAttribute->product->id;
                $orderProduct->count = $this->buyCount[$productAttributeId]['count'];
                $orderProduct->price = $productAttribute->price;
                $orderProduct->discount_price = 0.00;
                $orderProduct->coupon_id = 0;
                $orderProduct->product_attribute_info = $productAttribute->getProductAttributeInfo();

                if (!$orderProduct->save()) {
                    throw new Exception('创建订单商品失败');
                } else {
                    $orderProduct->createAwardCode();
                }
            }
        }
    }

    /** 生成摇奖号码 次方法仅支持一个订单一个宝贝的情况*/
    public function createAwardCode()
    {
        if ($this->orderProduct && $this->orderProduct->buy_type == OrderProduct::UNIT_PRICE) {
            // 购买方式是参与众筹,在支付成功后,生成摇奖编码
            $maxAwardCode = OrderAwardCode::find()->where(['product_id' => $this->orderProduct->product_id]);
            for ($maxAwardCode; $maxAwardCode > 0; $maxAwardCode--) {
                $awardCodeModel = new OrderAwardCode();
                $awardCodeModel->setAttributes([
                    'order_id' => $this->id,
                    'order_product_id' => $this->orderProduct->id,
                    'code' => $maxAwardCode,
                    'seller_id' => $maxAwardCode,
                ]);
            }

        }
    }

    /** 获取摇奖号码 */
    public function getAwardCodes()
    {
        return [
            '1234567890', '1234567891', '1234567892', '1234567893', '1234567894', '1234567895'
        ];
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

}
