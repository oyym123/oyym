<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/5
 * Time: 12:55
 */
namespace frontend\controllers;

use common\models\Base;
use common\models\Video;
use frontend\components\WebController;
use Yii;

class VideosController extends WebController
{
    public function init()
    {
        parent::init();
        if (empty($this->userId) && in_array(Yii::$app->requestedRoute, [
                'videos/create', 'videos/delete'
            ])
        ) {
            self::needLogin();
        }
    }

    /**
     * @SWG\Post(path="/videos/create",
     *   tags={"视频"},
     *   summary="上传视频",
     *   description="Author: OYYM",
     *  @SWG\Parameter(
     *     name="test.mp4",
     *     in="formData",
     *     default="1",
     *     description="视频名称",
     *     required=true,
     *     type="string",
     *   ),
     *  @SWG\Parameter(
     *     name="type",
     *     in="formData",
     *     default="1",
     *     description="视频类型",
     *     required=true,
     *     type="integer",
     *   ),
     *  @SWG\Parameter(
     *     name="id",
     *     in="formData",
     *     default="1",
     *     description="对应类型的ID",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="url",
     *     in="formData",
     *     default="demo321",
     *     description="七牛云返回存储文件路径",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="sort",
     *     in="formData",
     *     default="0",
     *     description="默认降序排列，默认为0",
     *     required=false,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="limit",
     *     in="formData",
     *     default="1",
     *     description="视频限制上传数量，默认为1",
     *     required=false,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionCreate()
    {
        $params = [
            'name' => Yii::$app->request->post('name'),
            'type' => Yii::$app->request->post('type'),
            'type_id' => Yii::$app->request->post('id'),
            'url' => Yii::$app->request->post('url'),
            'size_type' => Yii::$app->request->post('size', Video::SIZE_SMOOTH),
            'status' => Base::STATUS_ENABLE,
            'sort' => Yii::$app->request->post('sort', 0),
            'limit' => Yii::$app->request->post('limit', 1)
        ];

        if (Video::videoLimit($params) >= $params['limit']) {
            self::showMsg("只能上传{$params['limit']}个视频", 1);
        }
        Video::setVideo($params);
        self::showMsg('视频上传成功', 1);
    }

    /**
     * @SWG\Get(path="/videos/delete",
     *   tags={"视频"},
     *   summary="删除视频",
     *   description="Author: OYYM",
     *  @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="视频ID",
     *     required=true,
     *     type="integer",
     *   ),
     *  @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionDelete()
    {
        $params = [
            'id' => Yii::$app->request->get('id'),
        ];
        Video::deleteVideo($params);
        self::showMsg('视频删除成功', 1);
    }

    /**
     * @SWG\Get(path="/videos/get-all",
     *   tags={"视频"},
     *   summary="获取视频",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="视频类型对应的ID",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     default="1",
     *     description="视频类型",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionGetAll()
    {
        $params = [
            'type' => Yii::$app->request->get('type'),
            'type_id' => Yii::$app->request->get('id'),
            'skip' => $this->skip,
            'psize' => $this->psize
        ];
        self::showMsg(Video::getVideos($params), 1);
    }

    public function actionIndex()
    {
        $this->layout = 'blank';
        if (Yii::$app->request->get('key_words')) {
            return $this->render('index', [
                'data' => '',
                'keyWords' => Yii::$app->request->get('key_words'),
                'hotSearchWords' => ['猩球崛起', '蜘蛛侠', '战狼', '金刚狼3']
            ]);
        }
        return $this->render('index', [
            'data' => '',
            'keyWords' => '',
            'hotSearchWords' => ['猩球崛起', '蜘蛛侠', '战狼', '金刚狼3']
        ]);
    }

    public function actionSearchView()
    {
        $this->layout = 'blank';
        return $this->render('search', [
            'data' => '',
        ]);
    }

    public function actionView()
    {
        $this->layout = 'blank';
        return $this->render('view', [
            'data' => '',
        ]);
    }

    public function actionSearch()
    {

        $this->layout = 'blank';
        return $this->render('index', [
            'data' => '',
            'hotSearchWords' => ['猩球崛起', '蜘蛛侠', '战狼', '金刚狼3']
        ]);
    }


    public function actionWatch()
    {
        $this->layout = 'blank';
        return $this->render('watch', [
        ]);
    }
}