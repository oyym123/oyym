<?php

namespace frontend\controllers;

use common\models\Base;
use common\models\Attention;
use common\models\Product;
use yii\base\Exception;
use frontend\components\WebController;
use Yii;

class AttentionController extends WebController
{
    public function init()
    {
        parent::init();
        if (empty($this->userId)) {
            self::needLogin();
        }
    }

    /**
     * @SWG\Get(path="/attention/create",
     *   tags={"关注"},
     *   summary="关注",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     default="1",
     *     description="关注的类型，1=用户",
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
        $attention = new Attention();
        $params['type'] = Yii::$app->request->get('type');
        $params['type_id'] = Yii::$app->request->get('id');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $attention->checkType($params['type']);
            if ($model = $attention->attentionFlag($params)) {
                $model->status = Base::STATUS_ENABLE;
                if (!$model->save()) {
                    throw new Exception('关注失败');
                }
                $attention->saveTypeNumbers($params['type'], $params['type_id']);
                $transaction->commit();
                self::showMsg('关注成功', 1);
            }
            if (!$attention->create($params)) {
                throw new Exception('关注失败');
            }
            $attention->saveTypeNumbers($params['type'], $params['type_id']);
            $transaction->commit();
            self::showMsg('关注成功', 1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
    }

    /**
     * @SWG\Get(path="/attention/cancel",
     *   tags={"关注"},
     *   summary="取消关注",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     default="1",
     *     description="关注的类型，1=用户",
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
        $attention = new Attention();
        $params['type'] = Yii::$app->request->get('type');
        $params['type_id'] = Yii::$app->request->get('id');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $attention->checkType($params['type']);
            if ($model = $attention->attentionFlag($params)) {
                $model->status = Base::STATUS_DISABLE;
                if (!$model->save()) {
                    throw new Exception('关注失败');
                }
                $attention->saveTypeNumbers($params['type'], $params['type_id']);
                $transaction->commit();
                self::showMsg('已取消关注', 1);
            }
            self::showMsg('取消关注失败', -1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
    }

}