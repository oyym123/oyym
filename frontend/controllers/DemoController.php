<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/6/15
 * Time: 17:48
 */
namespace frontend\controllers;

use frontend\components\WebController;
use Yii;


/**
 * Class demoController
 * @package frontend\controllers
 */
class DemoController extends WebController
{
    /**
     * @SWG\Get(path="/demo/t?debug=1",
     *   tags={"demo"},
     *     summary="http://www.bejson.com/jsonviewernew/",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     default="1",
     *     description="",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *      response=200, description="successful operation",
     *   )
     * )
     */
    public function actionT()
    {
        var_dump(\Yii::$app->request->get('id'));
        var_dump(\Yii::$app->request->get('name'));
        $data = \Yii::$app->request->get();
        self::showMsg($data);
    }

    /**
     * @SWG\Post(path="/demo/test",
     *   tags={"demo"},
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="id",
     *     in="formData",
     *     default=1,
     *     description="",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   ),
     * )
     */
    public function actionTest()
    {
        $data1 = \Yii::$app->request->post('id');
        var_dump($data1);
        $data['id'] = $data1;
        self::showMsg($data);
    }

    /**
     * @SWG\Post(path="/demo/trim-double-quotes",
     *   tags={"demo"},
     *   summary="json单引号变双引号",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="json",
     *     in="formData",
     *     default="",
     *     description="单引号变双引号",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionTrimDoubleQuotes()
    {
        $json = Yii::$app->request->post('json');
        echo str_replace("'", '"', $json);
    }
}