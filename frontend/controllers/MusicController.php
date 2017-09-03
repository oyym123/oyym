<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/5
 * Time: 12:55
 */
namespace frontend\controllers;

use frontend\components\WebController;
use Yii;

class MusicController extends WebController
{
    public function actionIndex()
    {
        $this->layout = 'blank';
        return $this->render('index', [
            'data' => '',
        ]);
    }
}