<?php

/**
 * @link http://www.51zzzs.cn/
 * @copyright 2016 中国自主招生网
 * @author ouyangyumin@zgzzzs.com
 */

namespace frontend\controllers;

use common\helpers\Helper;
use common\models\Base;
use common\models\Comments;
use common\models\Image;
use common\models\Order;
use common\models\Product;
use common\models\UserBought;
use yii\base\Exception;
use common\models\UserInfo;
use frontend\components\WebController;
use yii;
use yii\filters\VerbFilter;

class CommentsController extends WebController
{
    public function init()
    {
        parent::init();
        if (empty($this->userId) && in_array(Yii::$app->requestedRoute, [
                'comments/create-video', 'comments/create-product', 'comments/reply-product', 'comments/product-page',
                'comments/create-product'
            ])
        ) {
            self::needLogin();
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /** 提交话题评论 */
    public function actionCreateProduct()
    {
        $contents = Helper::wordScreen(Yii::$app->request->post('contents')) ?: self::showMsg('提交的信息有敏感词', -200);
        $product_id = Yii::$app->request->post('product_id');
        $comment = new Comments();
        $comment->user_id = $this->userId;
        $comment->type = Comments::TYPE_PRODUCT;
        $comment->contents = $contents;
        $comment->type_id = $product_id;
        $comment->status = Base::STATUS_ENABLE;
        $comment->star = 5; //星级评论为5，用于兼容之前的视频评论
        $parentId = Yii::$app->request->post('comment_id') ?: 0;
        $commentLineId = Yii::$app->request->post('comment_line_id');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $product = $this->findProductModel($product_id);
            if ($comment->save()) {
                $comment->parent_id = $parentId;
                $comment->comment_line_id = $comment->id;
                if ($parentId && $commentLineId) {
                    $comment->comment_line_id = $commentLineId;
                    $childId = '';
                    $model = Comments::find()->where(['id' => $parentId, 'status' => Comments::STATUS_ENABLE])->one();
                    $childId .= $model->child_id ? $model->child_id . ',' . $comment->id : $comment->id;
                    $model->child_id = $childId;
                    $model->comments_count += 1;
                    $model->status = Base::STATUS_ENABLE;
                    if (!$model->save()) {
                        throw new Exception('保存评论失败');
                    }
                }
                if (!$comment->save()) {
                    throw new Exception('保存评论失败');
                }
            } else {
                throw new Exception('保存评论失败');
            }
            $userCount = Comments::find()
                ->where(["type_id" => $product_id])->andWhere(["type" => Comments::TYPE_PRODUCT])->count();
            $product->comments = $userCount;
            if (!$product->save()) {
                throw new Exception('统计评论失败');
            }
            $transaction->commit();

        } catch (yii\base\Exception $e) {

            $transaction->rollBack();

            self::showMsg($e->getMessage(), -1);
        }
        self::showMsg('评论已提交', 1);
    }


    /** 获取评论详情 */
    public function actionGetProduct()
    {
        $comment_id = Yii::$app->request->get('comment_id');
        $product_id = Yii::$app->request->get('product_id', 0);
        $comment_line_id = Yii::$app->request->get('comment_line_id');
        $data = [];
        if ($comment_id && $comment_line_id) {
            $comments = Comments::find()->where(['comment_line_id' => $comment_line_id])->orderBy('sort desc, created_at desc')->all();
            $topComment = Comments::find()->where(['id' => $comment_id])->one();
            $name = $topComment->user ? $topComment->user->getName() : '';
            $data = [];
            $data['top_comment'] = [
                'id' => $topComment->id,
                'user_photo' => ($x = $topComment->userInfo) ? $x->photoUrl() : '',
                'comment_count' => $topComment->comment_count,
                'like_count' => $topComment->like_count,
                'user_name' => $name,
                'user_id' => $topComment->user ? $topComment->user->id : '',
                'user_tag' => [],
                'contents' => $topComment->contents,
                'date' => Helper::tranTime($topComment->created_at),
            ];
            $data['list'] = [];
            foreach ($comments as $comment) {
                $userName = $comment->user ? $comment->user->getName() : '';
                if ($comment->parent_id != 0) {
                    $parentComment = Comments::find()->where(['id' => $comment->parent_id])->one();
                    $parentUserName = $parentComment->user ? $parentComment->user->getName() : '';
                    $data['list'][] = [
                        'id' => $comment->id,
                        'user_photo' => ($x = $comment->userInfo) ? $x->photoUrl() : '',
                        'comment_count' => $comment->comment_count,
                        'like_count' => $comment->like_count,
                        'user_name' => $userName,
                        'user_id' => $comment->user ? $comment->user->id : '',
                        'user_tag' => [],
                        'product_id' => $product_id,
                        'parent_id' => $parentComment->id,
                        'to_user_name' => $parentUserName,
                        'to_user_id' => $parentComment->user ? $parentComment->user->id : '',
                        'comment_line_id' => $comment_line_id,
                        'contents' => $comment->contents,
                        'date' => Helper::tranTime($comment->created_at),
                    ];
                }
            }
        }
        self::showMsg($data);
    }


    /** 获取我回复帖子的id和内容 */
    public function actionReplyProduct()
    {
        $model = Comments::find()->where(['user_id' => $this->userId])->orderBy('updated_at desc')->all();

        $data['list'] = [];
        foreach ($model as $item) {
            $userName = $item->user ? $item->user->username : '';
            if (Helper::isMobile($userName)) {
                $userName = Helper::formatMobile($userName);
            }
            $data['list'][] = [
                'id' => $item->id,
                'contents' => $item->contents ?: '',
                'product_id' => $item->productInfo ? yii\helpers\ArrayHelper::getValue($item->productInfo, 'id', 0) : 0,
                'product_content' => $item->productInfo ? yii\helpers\ArrayHelper::getValue($item->productInfo, 'contents', '') : '',
                'user_photo' => Image::getImages(['psize' => 1, 'type_id' => $this->userId, 'type' => Image::TYPE_USER_PHOTO]),
                'user_name' => $userName,
                'date' => Helper::tranTime($item->updated_at)
            ];
        }
        self::showMsg($data);
    }

    /** 获取点赞数量 */
    public
    function actionLikeCount($id)
    {
        $model = self::findModel($id);
        $model->like_count += 1;
        if (!$model->save()) {
            throw new Exception('保存失败');
        }
    }

    function findVideoModel($id)
    {
        if (($model = ProductVideo::findOne($id)) !== null) {
            return $model;
        } else {
            self::showMsg('没有数据', -1);
        }
    }

    function findProductModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            self::showMsg('没有数据', -1);
        }
    }

    function findModel($id)
    {
        if (($model = Comments::findOne($id)) !== null) {
            return $model;
        } else {
            self::showMsg('没有数据', -1);
        }
    }

    /** 取订单实体 */
    protected
    function findOrderModel($params)
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
