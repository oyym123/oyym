<?php


namespace frontend\controllers;

use frontend\components\WebController;
use Yii;
use Qiniu\Auth;

class FileTokenController extends WebController
{

    public function actionIndex()
    {
        require_once '../../vendor/qiniu/php-sdk/autoload.php';
        $auth = new Auth(Yii::$app->params['qiniu_access_key'], Yii::$app->params['qiniu_secret_key']);
        if (Yii::$app->request->get('video')) {
            $bkt = Yii::$app->params['qiniu_bucket_videos'];
            $upToken = $auth->uploadToken($bkt);
            $data = array('uptoken' => $upToken, 'host' => Yii::$app->params['qiniu_url_images']);
        } elseif (Yii::$app->request->get('video-private')) {
            $bkt = Yii::$app->params['qiniu_bucket_videos_private'];
            $upToken = $auth->uploadToken($bkt);
            $data = array('uptoken' => $upToken, 'host' => Yii::$app->params['qiniu_url_images']);
        } elseif (Yii::$app->request->get('image-private')) {
            $bkt = Yii::$app->params['qiniu_bucket_images_private'];
            $upToken = $auth->uploadToken($bkt);
            $data = array('uptoken' => $upToken, 'host' => Yii::$app->params['qiniu_url_images']);
        } else {
            $bkt = Yii::$app->params['qiniu_bucket_images'];
            $upToken = $auth->uploadToken($bkt);
            $data = array('uptoken' => $upToken, 'host' => Yii::$app->params['qiniu_url_images']);
        }
        self::showMsg($data);
    }
}
