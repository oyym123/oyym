<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $sn
 * @property integer $pay_type
 * @property string $pay_amount
 * @property string $product_amount
 * @property string $discount_amount
 * @property string $user_name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Order extends Base
{
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
            [['user_id', 'sn', 'pay_type', 'user_name', 'status', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'pay_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pay_amount', 'product_amount', 'discount_amount'], 'number'],
            [['sn'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'sn' => '订单号',
            'pay_type' => '支付类型',
            'pay_amount' => '支付金额',
            'product_amount' => '产品金额',
            'discount_amount' => '优惠后的金额',
            'user_name' => '用户名称',
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
            if (in_array($product['buy_type'], ['a_price', 'unit_price'])) { // 一口价 和 单价
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
                    $orderProduct->createAwardNumber();
                }
            }
        }
    }

    /** 生成摇奖号码 */
    public function createAwardNumber()
    {
        if ($this->buy_type == 'unit_price') {
            // 购买方式是参与众筹,需要生成摇奖编码

        }
    }

    /** 修改优惠券已被使用 */
    public function saveCoupon($newOrder)
    {
        foreach ($this->coupons as $coupon) {
            $userCoupon = UserCoupon::findOne(['user_id' => $newOrder->user_id, 'coupon_id' => $coupon->id]);
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
}
