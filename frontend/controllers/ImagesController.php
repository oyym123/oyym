<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/6
 * Time: 12:55
 */
namespace frontend\controllers;

use common\models\Base;
use common\models\Image;
use frontend\components\WebController;
use Yii;

class ImagesController extends WebController
{
    public function init()
    {
        parent::init();
        if (empty($this->userId) && in_array(Yii::$app->requestedRoute, [
                'images/create', 'images/delete'
            ])
        ) {
            self::needLogin();
        }
    }

    /**
     * @SWG\Post(path="/images/create?debug=1",
     *   tags={"图片"},
     *   summary="上传图片",
     *   description="Author: OYYM",
     *  @SWG\Parameter(
     *     name="test.mp4",
     *     in="formData",
     *     default="1",
     *     description="图片名称",
     *     required=true,
     *     type="string",
     *   ),
     *  @SWG\Parameter(
     *     name="type",
     *     in="formData",
     *     default="1",
     *     description="图片类型",
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
     *     description="图片限制上传数量，默认为1",
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
            'size_type' => Yii::$app->request->post('size', Image::SIZE_BIG),
            'status' => Base::STATUS_ENABLE,
            'sort' => Yii::$app->request->post('sort', 8),
            'limit' => Yii::$app->request->post('limit', 1)
        ];

        if (Image::imageLimit($params) >= $params['limit']) {
            self::showMsg("只能上传{$params['limit']}张图片", 1);
        }
        Image::setImage($params);
        self::showMsg('图片上传成功', 1);
    }

    /**
     * @SWG\Get(path="/images/delete?debug=1",
     *   tags={"图片"},
     *   summary="删除图片",
     *   description="Author: OYYM",
     *  @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="图片ID",
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
        Image::deleteImage($params);
        self::showMsg('图片删除成功', 1);
    }

    /**
     * @SWG\Get(path="/images/get-all?debug=1",
     *   tags={"图片"},
     *   summary="获取图片",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="图片类型对应的ID",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     default="1",
     *     description="图片类型",
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
        self::showMsg(Image::getImages($params), 1);
    }
}