<?php

/**
 * @link http://www.fangdazhongxin.com/
 * @copyright 2016 众筹夺宝
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
                'comments/create-product', 'comments/reply-product',
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

    /**
     * @SWG\Post(path="/comments/create-product",
     *   tags={"评论"},
     *   summary="添加产品评论",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="contents",
     *     in="formData",
     *     default="这手机真好看，I want buy !",
     *     description="评论内容",
     *     required=true,
     *     type="string",
     *   ),
     *    @SWG\Parameter(
     *     name="product_id",
     *     in="formData",
     *     default="1",
     *     description="产品ID",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="comment_id",
     *     in="formData",
     *     default="1",
     *     description="评论ID",
     *     required=false,
     *     type="integer",
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

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $product = $this->findProductModel($product_id);
            if ($comment->save()) {
                $comment->parent_id = $parentId;
                $comment->comment_line_id = $comment->id;
                if ($parentId) {
                    $childId = '';
                    $model = Comments::find()->where(['id' => $parentId, 'status' => Comments::STATUS_ENABLE])->one();
                    $childId .= $model->child_ids ? $model->child_ids . ',' . $comment->id : $comment->id;
                    $model->child_ids = $childId;
                    $model->comment_count += 1;
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

    /**
     * @SWG\Get(path="/comments/product",
     *   tags={"评论"},
     *   summary="获取评论",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="评论ID",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionProduct()
    {
        list($data, $state) = Comments::product(Yii::$app->request->get('id'));
        self::showMsg($data, $state);
    }


    /**
     * @SWG\Get(path="/comments/more-product",
     *   tags={"评论"},
     *   summary="更多评论",
     *   description="Author: OYYM",
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Parameter(name="id", in="query", default="1", description="产品ID", required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(name="offset", in="query", default="0", description="加载更多参数", required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionMoreProduct()
    {
        $data = Comments::getproduct(Yii::$app->request->get('id'), $this->offset);
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
