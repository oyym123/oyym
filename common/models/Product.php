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
 * @property integer $progress
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

    /** 返回宝贝模式 */
    public function modelTypeText()
    {
        return ArrayHelper::getValue(self::$modelType, $this->model, '');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'title', 'unit_price', 'created_by'], 'required'],
            [['sort', 'order_id', 'order_award_id', 'award_published_at', 'deleted_at', 'progress'], 'default', 'value' => 0],
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
            return 1; //进行中的页面，数量模式 没有一口价
        } elseif ($this->status == Product::STATUS_IN_PROGRESS && $this->model == Product::MODEL_NUMBER && $this->a_price > 0) {
            return 2; //进行中的页面，数量模式 有一口价
        } elseif ($this->status == Product::STATUS_IN_PROGRESS && $this->model == Product::MODEL_TIME) {
            return 3; //进行中的页面，时间模式
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
            return 1; //进行中的页面，数量模式 没有一口价
        } elseif ($this->status == Product::STATUS_IN_PROGRESS && $this->model == Product::MODEL_NUMBER && $this->a_price > 0) {
            return 2; //进行中的页面，数量模式 有一口价
        } elseif ($this->status == Product::STATUS_IN_PROGRESS && $this->model == Product::MODEL_TIME) {
            return 3; //进行中的页面，时间模式
        } else {
            return 1;//显示空页面
        }
    }

    /** 获取众筹进度 */
    public function getProgress()
    {
        if ($this->model == Product::MODEL_TIME) {
            $result = round((time() - intval($this->start_time)) / (intval($this->end_time) - intval($this->start_time)) * 100, 0);
            if ($result <= 100) {   //限制进度最大值为100%
                return $result;
            }
            return 100;
        }
    }

    /** 获取揭晓倒计时 */
    public function getPublishCountdown()
    {
        return max(0, $this->count_down - time());
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
    public function apiSearch($params, $offset, $limit)
    {
        $where = "";
        if (!empty($params['keywords'])) {
            $where .= " and (title like '%{$params['keywords']}%' or contents like '%{$params['keywords']}%') ";
        }
        $sql = "select * from `product` where `status` IN (20, 30)" . $where . self::searchType($params['sort_type']);

        $query = Product::findBySql($sql);
        $sql .= " limit $limit";
        $sql .= " offset $offset";
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
                return $sql = " ORDER BY  'sort' desc";
            case 'jindu':
                return $sql = " ORDER BY  'progress' desc";
            case 'danjia':
                return $sql = " ORDER BY  'unit_price' asc";
            case 'zongjia':
                return $sql = " ORDER BY  '(total*unit_price)' desc";
            case 'yikoujia':
                return $sql = " ORDER BY  'a_price' asc";
            case 'shijian':
                return $sql = " ORDER BY  'start_time' desc ";
            case 'zuixin':
                return $sql = " ORDER BY  'end_time' asc";
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
            'progress' => '进度',
        ];
    }

    /** 根据购买方式 返回不同的价格 */
    public function getPrice($buyType)
    {
        if ($buyType == OrderProduct::A_PRICE) {
            return $this->a_price;
        } else if ($buyType == OrderProduct::UNIT_PRICE) {
            return $this->unit_price;
        } else {
            return $this->a_price;
        }
    }

    public function getTag()
    {
        return $this->hasMany(ProductTag::className(), ['pid' => 'id']);
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

        if ($userId && $userId != $this->created_by) {
            return [1, '不允许修改别人的宝贝哦'];
        }

        if ($this->status != self::STATUS_IN_PROGRESS) {
            return [1, '宝贝状态不允许修改哦'];
        }

        if ($this->getSuccessOrderProduct()) {
            return [1, '宝贝已有人下单不允许修改'];
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
        $query = Image::find();
        $query->andWhere([
            'type' => Image::TYPE_PRODUCT,
            'type_id' => $this->id,
        ]);

        $item = $query->orderBy('sort desc')->one();

        return $item ? $item->qiniuUrl() : '';
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
                (($x = $this->order->buyer->info) ? $x->photoUrl($this->order->buyer_id) : '') : Yii::$app->params['defaultPhoto'];
        }
        return Yii::$app->params['defaultPhoto'];
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
        } else if (Yii::$app->user->id == $this->created_by) {
            return [
                [
                    'title' => '',
                    'url' => '',
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

    public function changeProgress()
    {
        $products = Product::findAll(['model' => Product::MODEL_TIME, 'status' => Product::STATUS_IN_PROGRESS]);
        foreach ($products as $product) {
            $product->progress = $product->getProgress();
            if (!$product->save()) {
                return [-1, $product->id . '产品进度保存失败'];
            }
        }
        return [1, '产品进度更新成功'];
    }

    /** 获取参加者人数 */
    public function getJoinCount()
    {
        return max(0, $this->total - $this->order_award_count);
//        return OrderAwardCode::getJoinCount($this->id);
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

    /** 获取该宝贝中支付成功的产品订单 */
    public function getSuccessOrderProduct($offset = 0)
    {
        $orderProduct = OrderProduct::find()->where(['order_product.pid' => $this->id])->
        join('LEFT JOIN', 'order', 'order.id = order_product.order_id')->andWhere(['>=', 'order.status', Order::STATUS_PAYED])
            ->orderBy('order_product.created_at desc')->offset($offset)->limit(20)->all();
        return $orderProduct;
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
        $r = '进行中';
        if ($this->status == Product::STATUS_NOT_SALE) {
            $r = '未上架';
        } elseif ($this->status == Product::STATUS_IN_PROGRESS) {
            $r = '进行中';
        } elseif ($this->status == Product::STATUS_WAIT_PUBLISH) {
            $r = '待揭晓';
        } elseif ($this->status == Product::STATUS_PUBLISHED) {
            $r = '已揭晓';
        }

        return $this->modelTypeText() . '_卖家_' . $r;
    }

    /** 买家-我参与的/买到的宝贝列表 样式布局 */
    public function buyerProductLayout()
    {
        $r = '进行中';
        if ($this->status == Product::STATUS_IN_PROGRESS) {
            $r = '进行中';
        } elseif ($this->status == Product::STATUS_WAIT_PUBLISH) {
            $r = '待揭晓';
        } elseif ($this->status == Product::STATUS_PUBLISHED) {
            $r = '已揭晓';
        }

        return $this->modelTypeText() . '_买家_' . $r;;
    }

    /** 卖家-我发布的/我卖出的 宝贝列表 控制链接跳转 */
    public function sellerProductUrlRoute()
    {
        if (in_array($this->status, [
            Product::STATUS_NOT_SALE, // 未上架
            Product::STATUS_IN_PROGRESS, // 进行中
            Product::STATUS_WAIT_PUBLISH, // 待揭晓
        ])) {
            return 'product'; // 跳转到宝贝详情
        }

        return 'order'; // 跳转到订单详情
    }

    /** 买家-我参与的/我买到的 宝贝列表 控制链接跳转 */
    public function buyerProductUrlRoute()
    {
        if (in_array($this->status, [
            Product::STATUS_NOT_SALE, // 未上架
            Product::STATUS_IN_PROGRESS, // 进行中
            Product::STATUS_WAIT_PUBLISH, // 待揭晓
        ])) {
            return 'product'; // 跳转到宝贝详情
        } else {
            // 已揭晓
            if ($this->order && $this->order->buyer_id == Yii::$app->user->identity->id) {
                // 中奖用户
                return 'order';
            }
        }

        return 'product'; // 跳转到订单详情
    }

    /** 卖家-我发布的/我卖出的 宝贝列表 */
    public function sellerProductActions()
    {
        $r = [
            [
                'title' => '编辑',
                'url' => 'edit',
            ],
            [
                'title' => '删除',
                'url' => 'delete',
            ],
        ];

        if ($this->status == Product::STATUS_NOT_SALE) {
            $r[] = [
                'title' => '上架',
                'url' => 'up_sell',
            ];
        } else {
            $r[] = [
                'title' => '下架',
                'url' => 'down_sell',
            ];
        }

        return $r;
    }

    /** 卖家发布的宝贝列表 字段 */
    public function sellerProductListField($products)
    {
        $r = [];
        foreach ($products as $key => $item) {
            $r[] = [
                'created_at' => $item->createdAt(),
                'product_id' => $item->id,
                'title' => $item->title,
                'img' => $item->headImg(), // 宝贝头图
                'layout' => $item->sellerProductLayout(),
                'status' => $item->getStatusText(),
                'total' => $item->total, // 总需要多少人次
                'order_award_count' => (int)$item->order_award_count, // 已参与人次
                'residual_total' => $item->getJoinCount(), // 剩余多少人次
                'residual_time' => $item->residualTime(), // 时间模式结束时间
                'progress' => $item->progress,
                'publish_countdown' => $item->getPublishCountdown(),// 揭晓倒计时
                'a_price' => $item->a_price,// 一口价
                'unit_price' => $item->unit_price,// 单价
                'url' => $item->sellerProductUrlRoute(), // 卖家宝贝路由
                'actions' => $item->sellerProductActions(), // 卖家宝贝动作
            ];
        }

        return $r;
    }

    /** 买家参与的宝贝列表 字段 */
    public function buyerProductListField($orderProducts)
    {
        $r = [];
        foreach ($orderProducts as $key => $item) {
            if ($item->product && $item->order) {
                $r[] = [
                    'created_at' => $item->order->createdAt(),
                    'product_id' => $item->product->id,
                    'title' => $item->product->title,
                    'img' => $item->product->headImg(), // 宝贝头图
                    'layout' => $item->product->buyerProductLayout(),
                    'status' => $item->product->getStatusText(),
                    'total' => $item->product->total, // 总需要多少人次
                    'order_award_count' => (int)$item->product->order_award_count, // 已参与人次
                    'residual_total' => $item->product->getJoinCount(), // 剩余多少人次
                    'residual_time' => $item->product->residualTime(), // 时间模式结束时间
                    'progress' => $item->product->progress,
                    'publish_countdown' => $item->product->getPublishCountdown(),// 揭晓倒计时
                    'a_price' => $item->product->a_price,// 一口价
                    'unit_price' => $item->product->unit_price,// 单价
                    'unit_price' => $item->order->pay_amount,// 支付金额
                    'url' => $item->product->buyerProductUrlRoute(), // 买家宝贝路由
                    'actions' => $item->order->buyerProductActions(), // 买家宝贝动作
                ];
            }
        }

        return $r;
    }

    /** 卖家-所有发布的的宝贝 */
    public function sellerProducts($params)
    {
        $query = Product::find()->where([
            'created_by' => $params['created_by'],
            'deleted_at' => 0,
        ]);

        $query->andFilterWhere(['status' => $params['status']]);

        $query->offset($params['offset'])->limit($this->psize);

        return [$this->sellerProductListField($query->all()), $query->count()];
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
    public function buyerAllProduct()
    {
        $query = OrderProduct::find()->where([
            'order_product.buyer_id' => $this->params['buyer_id']
        ]);

        $query->leftJoin('product', 'product.id=order_product.pid');

        $query->leftJoin('order', 'order.id=order_product.order_id');
        $query->andWhere([
            'order.deleted_at' => 0,
        ]);

        $query->andFilterWhere(['product.status' => ArrayHelper::getValue($this->params, 'status')]);

        $query->offset($this->params['offset'])->limit($this->psize);

        return [$this->buyerProductListField($query->all()), $query->count()];
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

    /** 是否允许开奖 */
    public function canOpenLottery()
    {
        if (in_array($this->status, [Product::STATUS_WAIT_PUBLISH])
            && Yii::$app->user->identity
            && $this->created_by > 0
            && $this->created_by == Yii::$app->user->identity->id
        ) {
            // 待揭晓状态 且 是卖家
            return 1;
        }
        return 0;
    }

    /** 开奖 */
    public function openLottery()
    {
        if (!$this->canOpenLottery()) {
            return [-1, '不允许开奖'];
        }

        $query = $this->getNumberAModel();

        $count = $query->count();
        if ($count < 1) {
            return [-1, '没有人参与不能开奖'];
        }
        $a = $query->sum('created_at');
        $b = mt_rand(10000, 99999);

        $luckCode = ($a + $b) % $count + 10000001;

        $awardCode = $query->andWhere(['code' => $luckCode])->one();

        if (!$awardCode) {
            return [-1, '开奖失败'];
        }

        // 修改宝贝状态
        $this->order_award_id = $awardCode->id;
        $this->order_id = $awardCode->order_id;
        $this->award_published_at = time();
        $this->random_code = $b;
        $this->status = Product::STATUS_PUBLISHED;

        try {
            $transaction = Yii::$app->db->beginTransaction();

            if (!$this->save()) {
                throw new Exception('更新宝贝开奖结果失败');
                Yii::error($this->getErrors(), 'product');
            }

            if (!$this->order) {
                throw new Exception('订单不存在');
                Yii::error($this->getErrors(), 'order');
            } else {
                $this->order->status = Order::STATUS_WAIT_SHIP; // 等待发货
                if (!$this->order->save()) {
                    throw new Exception('更新订单失败');
                    Yii::error($this->order->getErrors(), 'order');
                }
            }

            $transaction->commit();
            return [0, '您成功抽中一名中奖用户'];
        } catch (Exception $e) {
            $transaction->rollBack();
            return [-1, $e->getMessage()];
        }
    }

    /** 计算开奖编码A */
    public function getNumberAModel()
    {
        return OrderAwardCode::find()->where(['product_id' => $this->id, 'deleted_at' => 0]);
    }

    /** 判断宝贝是否已有订单产生 */
    public function hasOrder()
    {
        return $this->order_award_count > 0;
    }

    /** 上架 */
    public function upSell()
    {
        if ($this->status != Product::STATUS_NOT_SALE) {
            return [-1, '必须下架中的宝贝才可以上架'];
        }

        $this->status = Product::STATUS_IN_PROGRESS;
        if ($this->save()) {
            return [0, '宝贝已上架'];
        }

//         print_r($this->getErrors());
        return [0, '宝贝上架失败'];
    }

    /** 下架 */
    public function downSell()
    {
        if ($this->hasOrder()) {
            return [-1, '已有用户参与, 不允许下架'];
        }

        $this->status = Product::STATUS_NOT_SALE;
        if ($this->save()) {
            return [0, '宝贝已下架'];
        }

//         print_r($this->getErrors());
        return [0, '宝贝下架失败'];
    }

    /** 数量模式下, 计算新的进度值, 值为0到100 */
    public function getNewProgress($NewOrderAwardCount)
    {
        return min(100, number_format($NewOrderAwardCount / $this->total, 2, '.', '') * 100);
    }

    /** 结束时间 */
    public function residualTime()
    {
        return $this->end_time ? date('Y-m-d H:i:s', $this->end_time) : '';
    }
}

