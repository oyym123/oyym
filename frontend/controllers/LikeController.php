<?php

namespace frontend\controllers;

use common\models\Base;
use common\models\Like;
use yii\base\Exception;
use frontend\components\WebController;
use Yii;

class LikeController extends WebController
{
    public function init()
    {
        parent::init();
        if (empty($this->userId)) {
            self::needLogin();
        }
    }

    /**
     * @SWG\Get(path="/like/create",
     *   tags={"点赞"},
     *   summary="点赞",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     default="1",
     *     description="点赞的类型，1=产品,2=评论",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="该类型对应的id",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="ky-token",
     *     in="header",
     *     default="1",
     *     description="用户ky-token",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   )
     * )
     */
    public function actionCreate()
    {
        $like = new Like();
        $params['type'] = Yii::$app->request->get('type');
        $params['type_id'] = Yii::$app->request->get('id');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $like->checkType($params['type']);
            if ($model = $like->LikeFlag($params)) {
                $model->status = Base::STATUS_ENABLE;
                if (!$model->save()) {
                    throw new Exception('点赞失败');
                }
                $like->saveTypeNumbers($params['type'], $params['type_id']);
                $transaction->commit();
                self::showMsg('点赞成功', 1);
            }

            if (!$like->create($params)) {
                throw new Exception('点赞失败');
            }
            $like->saveTypeNumbers($params['type'], $params['type_id']);
            $transaction->commit();
            self::showMsg('点赞成功', 1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
    }


    /**
     * @SWG\Get(path="/like/cancel",
     *   tags={"点赞"},
     *   summary="取消点赞",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     default="1",
     *     description="点赞的类型，1=产品",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="该类型对应的id",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="ky-token",
     *     in="header",
     *     default="1",
     *     description="用户ky-token",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   )
     * )
     */
    public function actionCancel()
    {
        $like = new Like();
        $params['type'] = Yii::$app->request->get('type');
        $params['type_id'] = Yii::$app->request->get('id');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $like->checkType($params['type']);
            if ($model = $like->LikeFlag($params)) {
                $model->status = Base::STATUS_DISABLE;
                if (!$model->save()) {
                    throw new Exception('取消点赞失败');
                }
                $like->saveTypeNumbers($params['type'], $params['type_id']);
                $transaction->commit();
                self::showMsg('已取消点赞', 1);
            }
            self::showMsg('取消点赞失败', -1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
    }
}