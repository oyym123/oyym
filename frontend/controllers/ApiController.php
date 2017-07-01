<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/6/16
 * Time: 9:25
 */
namespace frontend\controllers;


class ApiController extends \yii\base\Controller
{
    function actionList2()
    {
        $this->layout = 'blank';
        return $this->render('index');
    }
}