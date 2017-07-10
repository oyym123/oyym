<?php


namespace frontend\controllers;

use frontend\components\WebController;
use Yii;
use Qiniu\Auth;

class FileTokenController extends WebController
{
    /**
     * @SWG\Get(path="/file-token/index?debug=1",
     *   tags={"获取七牛云token"},
     *   summary="获取七牛云token",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="token_type",
     *     in="query",
     *     default="1",
     *     description="不传参数默认给image的token, 1 = video公有，2 = video私有桶 , 3 = image私有",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionIndex()
    {
        require_once '../../vendor/qiniu/php-sdk/autoload.php';
        $auth = new Auth(Yii::$app->params['qiniu_access_key'], Yii::$app->params['qiniu_secret_key']);
        if (Yii::$app->request->get('token_type') == 1) {
            $bkt = Yii::$app->params['qiniu_bucket_videos'];
            $upToken = $auth->uploadToken($bkt);
            $data = array('uptoken' => $upToken, 'host' => Yii::$app->params['qiniu_url_images']);
        } elseif (Yii::$app->request->get('token_type') == 2) {
            $bkt = Yii::$app->params['qiniu_bucket_videos_private'];
            $upToken = $auth->uploadToken($bkt);
            $data = array('uptoken' => $upToken, 'host' => Yii::$app->params['qiniu_url_images']);
        } elseif (Yii::$app->request->get('token_type' == 3)) {
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
