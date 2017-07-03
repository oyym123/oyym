<?php

namespace frontend\controllers;

use common\models\Base;
use common\models\Like;
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
     * @SWG\Get(path="/like/create?debug=1",
     *   tags={"点赞"},
     *   summary="点赞",
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
        if ($model = $like->LikeFlag($params)) {
            $model->status = Base::STATUS_ENABLE;
            if ($model->save()) {
                self::showMsg('点赞成功', 1);
            }
            self::showMsg('点赞失败', -1);
        }

        if ($like->create($params)) {
            self::showMsg('点赞成功', 1);
        }
        self::showMsg('点赞失败', -1);
    }

    /**
     * @SWG\Get(path="/like/cancel?debug=1",
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
        if ($model = $like->LikeFlag($params)) {
            $model->status = Base::STATUS_DISABLE;
            if ($model->save()) {
                self::showMsg('已取消点赞', 1);
            }
            self::showMsg('取消点赞失败', -1);
        }
        self::showMsg('取消点赞失败', -1);
    }

}