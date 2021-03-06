<?php

namespace frontend\controllers;

use common\models\Base;
use common\models\Collection;
use common\models\Product;
use yii\base\Exception;
use frontend\components\WebController;
use Yii;

class CollectionController extends WebController
{
    public function init()
    {
        parent::init();
        if (empty($this->userId)) {
            self::needLogin();
        }
    }

    /**
     * @SWG\Get(path="/collection/create",
     *   tags={"收藏"},
     *   summary="收藏",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     default="1",
     *     description="收藏的类型，1=产品",
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
     *  @SWG\Parameter(
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
        $collection = new Collection();
        $params['type'] = Yii::$app->request->get('type');
        $params['type_id'] = Yii::$app->request->get('id');
        $transaction = Yii::$app->db->beginTransaction();
        try {
           $collection->checkType($params['type']);
            if ($model = $collection->collectionFlag($params)) {
                $model->status = Base::STATUS_ENABLE;
                if (!$model->save()) {
                    throw new Exception('收藏失败');
                }
                $collection->saveTypeNumbers($params['type'], $params['type_id']);
                $transaction->commit();
                self::showMsg('收藏成功', 1);
            }
            if (!$collection->create($params)) {
                throw new Exception('收藏失败');
            }
            $collection->saveTypeNumbers($params['type'], $params['type_id']);
            $transaction->commit();
            self::showMsg('收藏成功', 1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
    }

    /**
     * @SWG\Get(path="/collection/cancel",
     *   tags={"收藏"},
     *   summary="取消收藏",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     default="1",
     *     description="收藏的类型，1=产品",
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
     *  @SWG\Parameter(
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
        $collection = new Collection();
        $params['type'] = Yii::$app->request->get('type');
        $params['type_id'] = Yii::$app->request->get('id');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $collection->checkType($params['type']);
            if ($model = $collection->collectionFlag($params)) {
                $model->status = Base::STATUS_DISABLE;
                if (!$model->save()) {
                    throw new Exception('收藏失败');
                }
                $collection->saveTypeNumbers($params['type'], $params['type_id']);
                $transaction->commit();
                self::showMsg('已取消收藏', 1);
            }
            self::showMsg('取消收藏失败', -1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
    }

}