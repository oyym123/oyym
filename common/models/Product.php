<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $model
 * @property integer $likes
 * @property integer $collections
 * @property string $title
 * @property string $price
 * @property string $original_price
 * @property integer $total
 * @property integer $created_by
 * @property string $contents
 * @property string $detail_address
 * @property string $lat
 * @property integer $count_down
 * @property string $lng
 * @property integer $watches
 * @property integer $comments
 * @property string $a_price
 * @property string $unit_price
 * @property string $freight
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $sort
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Product extends Base
{
    const STATUS_NOT_SALE = 10; // 未上架
    const STATUS_IN_PROGRESS = 20; // 进行中
    const STATUS_WAIT_PUBLISH = 30; // 待揭晓
    const STATUS_PUBLISHED = 40; // 已揭晓 是宝贝的终点状态
    const STATUS_CANCELED = 50; // 已取消


    /** 宝贝状态 */
    public static $status = [
        self::STATUS_NOT_SALE => '未上架',
        self::STATUS_IN_PROGRESS => '进行中',
        self::STATUS_WAIT_PUBLISH => '待揭晓',
        self::STATUS_PUBLISHED => '已揭晓',
        self::STATUS_CANCELED => '已取消',
    ];

    const TYPE_NOT_EXAMINE = 0; //未审核
    const TYPE_ALREADY_EXAMINE = 1; //已审核

    /** 审核状态(后台使用) */
    public static $examineStatus = [
        self::TYPE_NOT_EXAMINE => '未审核',
        self::TYPE_ALREADY_EXAMINE => '已审核',
    ];

    const MODEL_NUMBER = 1; // 数量模式
    const MODEL_TIME = 2; // 时间模式

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    public static $modelType = [
        Product::MODEL_NUMBER => '数量模式',
        Product::MODEL_TIME => '时间模式',
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'title', 'created_at', 'updated_at', 'unit_price', 'created_by'], 'required'],
            [['sort', 'order_id', 'order_award_id', 'award_published_at', 'deleted_at'], 'default', 'value' => 0],
            [['sort', 'order_id', 'order_award_id', 'award_published_at', 'deleted_at', 'total'], 'default', 'value' => 0],
            [['type_id', 'watches', 'comments', 'sort', 'status', 'created_at', 'updated_at', 'total', 'random_code'], 'integer'],
            [['price', 'original_price', 'freight', 'unit_price', 'a_price'], 'number'],
            [['contents'], 'string'],
            [['title', 'lat', 'lng'], 'string', 'max' => 255],
        ];
    }

    /** 取宝贝状态 */
    public function getStatusText()
    {
        return ArrayHelper::getValue(self::$status, $this->status, '');
    }

    /** 宝贝详情接口下放布局样式id, 用于控制客户端展示不同的布局  */
    public function viewLayoutType()
    {
        if ($this->status == Product::STATUS_IN_PROGRESS && $this->model == Product::MODEL_NUMBER && empty($this->a_price)) {
            return 1; //正在进行的页面，数量模式 没有一口价
        } elseif ($this->status == Product::STATUS_IN_PROGRESS && $this->model == Product::MODEL_NUMBER && $this->a_price > 0) {
            return 2; //正在进行的页面，数量模式 有一口价
        } elseif ($this->status == Product::STATUS_IN_PROGRESS && $this->model == Product::MODEL_TIME) {
            return 3; //正在进行的页面，时间模式
        } elseif ($this->status == Product::STATUS_WAIT_PUBLISH) {
            return 4; //卖家用户的待揭晓页面，显示“我来揭晓” ，买家用户的待揭晓页面，显示“请等待系统揭晓”
        } elseif ($this->status = self::STATUS_PUBLISHED) {
            return 5; //已揭晓 , 一口价 //已揭晓 ，获奖
        } else {
            return 6; //显示空页面
        }
    }

    /** 宝贝列表接口下放布局样式id, 用于控制客户端展示不同的布局  */
    public function listLayoutType()
    {
        if ($this->status == Product::STATUS_IN_PROGRESS && $this->model == Product::MODEL_NUMBER && empty($this->a_price)) {
            return 1; //正在进行的页面，数量模式 没有一口价
        } elseif ($this->status == Product::STATUS_IN_PROGRESS && $this->model == Product::MODEL_NUMBER && $this->a_price > 0) {
            return 2; //正在进行的页面，数量模式 有一口价
        } elseif ($this->status == Product::STATUS_IN_PROGRESS && $this->model == Product::MODEL_TIME) {
            return 3; //正在进行的页面，时间模式
        } else {
            return 1;//显示空页面
        }
    }


    /** 获取众筹进度 */
    public function getProgress($participants)
    {
        if ($this->model == Product::MODEL_TIME) {
            $result = round((time() - intval($this->start_time)) / (intval($this->end_time) - intval($this->start_time)) * 100, 0);
            if ($result <= 100) {   //限制进度最大值为100%
                return $result;
            }
            return 100;
        }

        if ($this->total) {
            $result = round($participants / $this->total * 100, 0);
            if ($result <= 100) {   //限制进度最大值为100%
                return $result;
            }
            return 100;
        }
        return 0;
    }

    /** 获取揭晓倒计时 */
    public function getPublishCountdown()
    {
        if ($this->count_down) {
            return time() - $this->count_down;
        }
        return 365 * 24 * 3600;
    }

    /** 产品收藏数量保存 */
    public static function collection($id, $count)
    {
        $model = self::findModel($id);
        $model->collections = $count;
        if (!$model->save()) {
            throw new Exception('宝贝收藏失败!');
        }
    }

    /** 产品点赞数量保存 */
    public static function like($id, $count)
    {
        $model = self::findModel($id);
        $model->likes = $count;
        if (!$model->save()) {
            throw new Exception('宝贝点赞失败!');
        }
    }

    /** 获取产品图片 */
    public function getImages()
    {
        return Image::getImages(['type' => Image::TYPE_PRODUCT, 'type_id' => $this->id]);
    }

    /** 获取产品视频 */
    public function getVideos()
    {
        return Video::getVideos(['type' => Video::TYPE_PRODUCT, 'type_id' => $this->id]);
    }

    /** 获取是否点赞标志 */
    public function getIsLike()
    {
        $query = Like::likeFlag(['type' => Like::TYPE_PRODUCT, 'type_id' => $this->id, 'status' => self::STATUS_ENABLE]);
        return $query ? self::STATUS_ENABLE : self::STATUS_DISABLE;
    }

    /** 获取是否收藏标志 */
    public function getIsCollection()
    {
        $query = Collection::collectionFlag(['type' => Collection::TYPE_PRODUCT, 'type_id' => $this->id, 'status' => self::STATUS_ENABLE]);
        return $query ? self::STATUS_ENABLE : self::STATUS_DISABLE;
    }

    /** 揭晓模式判断 */
    public function viewAnnouncedType()
    {
        if ($this->created_by != Yii::$app->user->id && $this->status == Product::STATUS_WAIT_PUBLISH) {
            return [
                'layout' => 2,
                'title' => '请等待系统揭晓'
            ]; // 买家用户的待揭晓页面，显示“请等待系统揭晓”
        } elseif ($this->created_by == Yii::$app->user->id && $this->status == Product::STATUS_WAIT_PUBLISH) {
            return [
                'layout' => 1,
                'title' => '我来揭晓'
            ]; //卖家用户的待揭晓页面，显示“我来揭晓”
        }
    }

    /** 产品搜索 */
    public function apiSearch($key)
    {
        $sql = "select * from `product` where `status` IN (20, 30)" . self::searchType($key);
        $query = Product::findBySql($sql);
        return [
            Product::findBySql($sql)->all(),
            $query->count(),
        ];
    }

    /** 默认升序 */
    public static function searchType($key)
    {
        switch ($key) {
            case 'tuijian':
                return $sql = "andWhere(['type_id' => 'id'])";
            case 'jindu':
                return $sql = "sort()";
            case 'danjia':
                return $sql = "  ORDER BY  'unit_price' asc";
            case 'zongjia':
                return $sql = "sort('total asc')";
            case 'yikoujia':
                return $sql = "andWhere(['>','a_price', 0])->sort('a_price asc')";
            case 'shijian':
                return $sql = "sort('start_time desc,end_time asc')";
            case 'zuixin':
                return $sql = "andWhere(['>' , 'start_time', now()+864000])->sort('start_time desc , created_at desc')";
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => '产品类型',
            'title' => '名称',
            'a_price' => '一口价',
            'unit_price' => '单价',
            'price' => '价格',
            'original_price' => '原价',
            'total' => '总数量',
            'contents' => '内容',
            'watches' => '观看人数',
            'comments' => '评论人数',
            'freight' => '运费',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'deleted_at' => '删除时间',
            'random_code' => '随机码B, 例如时时彩的5位中奖码',
            'order_award_id' => '中奖id',
            'order_id' => '中奖订单id',
            'award_published_at' => '中奖揭晓时间',
        ];
    }

    /**
     * 判断商品是否可以购买
     */
    public function canBuy($userId = 0)
    {
        if ($this->status != self::STATUS_IN_PROGRESS) {
            return [1, '宝贝状态不允许购买'];
        }

        if ($userId && $userId == $this->created_by) {
            return [1, '不允许购买自己的宝贝哦'];
        }
        return [0, ''];
    }

    /**
     * 是否可以修改, 如果有交易产生,则不允许修改
     */
    public function canUpdate($userId = 0)
    {
        if ($this->status != self::STATUS_IN_PROGRESS) {
            return [1, '宝贝状态不允许修改哦'];
        }

        if ($userId && $userId != $this->created_by) {
            return [1, '不允许修改别人的宝贝哦'];
        }
        return [0, ''];
    }

    /**
     * 是否可以修改, 如果有交易产生,则不允许修改
     */
    public function canDelete($userId)
    {
        if ($this->status == self::STATUS_IN_PROGRESS) {
            return [1, '宝贝状态不允许删除哦'];
        }

        if ($userId && $userId != $this->created_by) {
            return [1, '不允许删除别人的宝贝哦'];
        }
        return [0, ''];
    }


    /** 商品头图 */
    public function headImg()
    {


    }

    /** 获取中奖者姓名 */
    public function getLuckUserName()
    {
        if ($this->status == self::STATUS_PUBLISHED) {
            return ($this->order ? $this->order->buyer : '') ? $this->order->buyer->getName() : '佚名';
        }
        return '佚名';
    }

    /** 获取中奖者头像 */
    public function getLuckUserPhoto()
    {
        if ($this->status == self::STATUS_PUBLISHED) {
            return ($this->order ? $this->order->buyer : '') ?
                (($x = $this->order->buyer->info) ? $x->photoUrl($this->order->buyer_id) : '') : '佚名';
        }
        return '佚名';
    }

    /** 获取中奖方式 */
    public function getLuckType()
    {
        $buyType = $this->orderProduct ? $this->orderProduct->buy_type : '';
        if ($buyType == OrderProduct::A_PRICE && $this->status == self::STATUS_PUBLISHED) {
            return "一口价{$this->a_price}元购买";
        }

        if ($buyType == OrderProduct::UNIT_PRICE && $this->status == self::STATUS_PUBLISHED) {
            return "众筹夺宝";
        }

        return '';
    }

    /** 参与按钮的样式 */
    public function buttonType()
    {
        if ($this->model == self::MODEL_NUMBER && ($this->a_price > 0)) {
            return [
                [
                    'title' => '立即参与',
                    'url' => 'join_now',
                ],
                [
                    'title' => '一口价',
                    'url' => 'a_price',
                ]
            ];
        } else {
            return [
                [
                    'title' => '立即参与',
                    'url' => 'join_now',
                ]
            ];
        }
    }

    /** 获取参加者人数 */
    public function getJoinCount()
    {
        return OrderAwardCode::getJoinCount($this->id);
    }

    /** 获取该宝贝中奖订单 */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /** 获取该宝贝中产品订单 */
    public function getOrderProduct()
    {
        return $this->hasMany(OrderProduct::className(), ['pid' => 'id']);
    }

    /** 获取用户信息 */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /** 获取用户详情 */
    public function getUserInfo()
    {
        return $this->hasOne(UserInfo::className(), ['user_id' => 'created_by']);
    }

    /** 获取获奖幸运号 */
    public function getOrderAwardCode()
    {
        return $this->hasOne(OrderAwardCode::className(), ['id' => 'order_award_id']);
    }

    /** 获取获奖幸运号 */
    public function getOrderAward()
    {
        return $this->hasMany(OrderAwardCode::className(), ['product_id' => 'id']);
    }

    /** 取最大的摇奖代码 */
    public function getMaxAwardCode()
    {
        $maxAwardCode = OrderAwardCode::find()->where([
            'product_id' => $this->id,
            'deleted_at' => 0
        ])->one();

        return $maxAwardCode ? $maxAwardCode->code : 10000001;
    }

    /** 卖家-我发布的/卖出的宝贝列表 样式布局 */
    public function sellerProductLayout()
    {
        $r = '买家_正在进行';
        if ($this->status == Product::STATUS_NOT_SALE) {
            $r = '买家_未上架';
        } elseif ($this->status == Product::STATUS_IN_PROGRESS) {
            $r = '买家_正在进行';
        } elseif ($this->status == Product::STATUS_WAIT_PUBLISH) {
            $r = '买家_待揭晓';
        } elseif ($this->status == Product::STATUS_PUBLISHED) {
            // 已揭晓
            if ($luckOrder = $this->order) {
                if ($luckOrder->status == Order::STATUS_WAIT_SHIPPING) {
                    $r = '卖家_待发货';
                } elseif ($luckOrder->status == Order::STATUS_SHIPPED) {
                    $r = '卖家_已发货';
                } elseif ($luckOrder->status == Order::STATUS_CONFIRM_RECEIVING) {
                    // 已签收 如果双方评价, 则状态为 已完成
                    if (in_array($luckOrder->evaluation_status, [0, 1])) {
                        $r = '卖家_待评价';
                    }
                } elseif ($luckOrder->status == Order::STATUS_COMPLETE) {
                    // 双方都评价后进入该状态
                    $r = '卖家_已完成';
                }
            }
        }

        return $r;
    }

    /** 买家-我参与的/买到的宝贝列表 样式布局 */
    public function buyerProductLayout()
    {
        $r = '买家_正在进行';
        if ($this->status == Product::STATUS_IN_PROGRESS) {
            $r = '买家_正在进行';
        } elseif ($this->status == Product::STATUS_WAIT_PUBLISH) {
            $r = '买家_待揭晓';
        } elseif ($this->status == Product::STATUS_PUBLISHED) {
            // 已揭晓
            if ($this->order && $this->order->buyer_id == Yii::$app->user->identity->id) {
                if ($this->order->status == Order::STATUS_WAIT_SHIPPING) {
                    $r = '买家_待发货';
                } elseif ($this->order->status == Order::STATUS_SHIPPED) {
                    $r = '买家_已发货';
                } elseif ($this->order->status == Order::STATUS_CONFIRM_RECEIVING) {
                    // 已签收
                    if (in_array($this->order->evaluation_status, [0, 1])) {
                        $r = '卖家_待评价';
                    }
                } elseif ($this->order->status == Order::STATUS_COMPLETE) {
                    // 双方都评价后进入该状态
                    $r = '卖家_已完成';
                }
            } else {
                $r = '买家_已揭晓'; // 未中奖用户
            }
        }

        return $r;
    }

    /** 卖家-我发布的产品列表 链接地址 */
    public function sellerProductUrl()
    {

    }

    /** 卖家宝贝列表 字段 */
    public function sellerProductListField($items)
    {
        $r = [];
        foreach ($items as $key => $item) {
            $r[] = [
                'layout' => function ($item) {
                    $r = '数量模式_未上架';
                    if ($item->model == Product::MODEL_NUMBER) { // 数量模式
                        if ($item->status == Product::STATUS_NOT_SALE) { // 未上架
                            return '数量模式_未上架';
                        } elseif ($item->status == Product::STATUS_IN_PROGRESS) { // 进行中
                            return '数量模式_进行中';
                        } elseif ($item->status == Product::STATUS_WAIT_PUBLISH) { // 待揭晓
                            return '数量模式_待揭晓';
                        } elseif ($item->status == Product::STATUS_PUBLISHED) { // 已揭晓
                            return '数量模式_已揭晓';
                        }
                    } else { // 时间模式
                        if ($item->status == Product::STATUS_NOT_SALE) { // 未上架
                            return '时间模式_未上架';
                        } elseif ($item->status == Product::STATUS_IN_PROGRESS) { // 进行中
                            return '时间模式_进行中';
                        } elseif ($item->status == Product::STATUS_WAIT_PUBLISH) { // 待揭晓
                            return '时间模式_待揭晓';
                        } elseif ($item->status == Product::STATUS_PUBLISHED) { // 已揭晓
                            return '时间模式_已揭晓';
                        }
                    }

                    return $r;
                },
                'id' => $item->id,
                'title' => $item->title,
                'layout' => $item->sellerProductLayout(),
                'status' => $item->getStatusText(),
                'total' => $item->total, // 总需要多少人次
                'order_award_count' => $item->order_award_count, // 已参与人次
                'residual_total' => max(0, $item->total - $item->order_award_count), // 剩余多少人次
                'progress' => $item->getPr,
                'publish_countdown' => '',// 揭晓倒计时
                'a_price' => '',// 一口价
                'unit_price' => '',// 单价
                'url' => $item->sellerProductAction(),
                'actions' => $item->sellerProductActions(),
            ];
        }

        return $r;
    }

    /** 卖家-所有发布的的宝贝 */
    public function myProducts($params)
    {
        $query = Product::find()->where([
            'created_by' => $params['seller_id'],
            'deleted_at' => 0
        ]);

        if ($params['status']) {
            $query->andWhere(['status' => $params['status']]);
        }

        $query->offset($params['offset'])->limit($this->psize);

        return [$this->sellerProductListField($query->all()), $query->count()];
    }

    /** 卖家-进行中的宝贝 */
    public function sellerInProgress()
    {

    }

    /** 卖家-待揭晓的宝贝 */
    public function sellerWaitPublish()
    {

    }

    /** 卖家-已下架的宝贝 */
    public function cancel()
    {

    }

    // ---------------------------------------卖家/买家分割线-------------------------------------- //

    /** 买家-所有参与的宝贝 */
    public function buyerAll()
    {

    }

    /** 买家-进行中的宝贝 */
    public function buyerInProgress()
    {

    }

    /** 买家-待揭晓的宝贝 */
    public function buyerWaitPublished()
    {

    }

    /** 买家-已揭晓的宝贝 (可能中奖人不是自己) */
    public function buyerPublished()
    {

    }

    /** 用户对宝贝可以进行的操作,编辑删除等 */
    public function userActions()
    {

    }

    public static function findModel($id)
    {
        if (($model = Product::findOne($id))) {
            return $model;
        } else {
            throw new Exception('宝贝不存在!');
        }
    }
}

