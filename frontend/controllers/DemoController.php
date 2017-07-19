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

    public function actionT()
    {
        var_dump(\Yii::$app->request->get('id'));
        var_dump(\Yii::$app->request->get('name'));
        $data = \Yii::$app->request->get();
        self::showMsg($data);
    }

    public function actionTest()
    {
        $data1 = \Yii::$app->request->post('id');
        var_dump($data1);
        $data['id'] = $data1;
        self::showMsg($data);
    }

    public function actionTrimDoubleQuotes()
    {
        $json = Yii::$app->request->post('json');
        echo str_replace("'", '"', $json);
    }
}