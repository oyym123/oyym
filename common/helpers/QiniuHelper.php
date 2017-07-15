<?php

namespace common\helpers;

use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;
use Qiniu\Storage\BucketManager;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseArrayHelper;

// 引入鉴权类

class QiniuHelper extends BaseArrayHelper
{
    public static function auth2()
    {
        require_once '../../vendor/qiniu/php-sdk/autoload.php';

        $auth = new Auth(Yii::$app->params['qiniu_access_key'], Yii::$app->params['qiniu_secret_key']);
        $bkt = Yii::$app->params['qiniu_bucket_videos'];
        $upToken = $auth->uploadToken($bkt);
        return $upToken;
    }

    /** 获取七牛图片存储地址 */
    public static function downloadImageUrl($url, $key)
    {
        require_once Yii::getAlias('@vendor') . '/qiniu/php-sdk/autoload.php';

        //初始化Auth状态
        $auth = new Auth(Yii::$app->params['qiniu_access_key'], Yii::$app->params['qiniu_secret_key']);

        //baseUrl构造成私有空间的域名/key的形式
        return $auth->privateDownloadUrl($url . $key) . '&imageMogr2/strip';
    }


    /** 获取七牛图片存储地址 */
    public static function downloadVideoUrl($url, $key)
    {
        require_once Yii::getAlias('@vendor') . '/qiniu/php-sdk/autoload.php';

        //初始化Auth状态
        $auth = new Auth(Yii::$app->params['qiniu_access_key'], Yii::$app->params['qiniu_secret_key']);

        //baseUrl构造成私有空间的域名/key的形式
        return $auth->privateDownloadUrl($url . $key);
    }

    /**视频操作 */
    public function pfop($url)
    {
        require_once '../../vendor/qiniu/php-sdk/autoload.php';
        $auth = new Auth(Yii::$app->params['qiniu_access_key'], Yii::$app->params['qiniu_secret_key']);
        $bkt = Yii::$app->request->get('bkt') ?: Yii::$app->params['qiniu_bucket_videos'];

        //要转码的文件所在的空间和文件名
        $bucket = Yii::$app->params['qiniu_bucket_videos'];
        $key = $url;

        //转码时使用的队列名称
        $pipeline = 'video_mp4';
        $pfop = new PersistentFop($auth, $bucket, $pipeline);

        //要进行转码的转码操作
        $fops = "avthumb/mp4/s/640x360/vb/1.25m";
        list($id, $err) = $pfop->execute($key, $fops);
        //查询转码的进度和状态
        list($ret, $err) = $pfop->status($id);
        if ($err != null) {
            return $url;
        } else {
            return $ret['items'][0]['key'];
        }
    }

    /**视频截图操作*/
    public static function screenShot($url, $second)
    {
        $link = self::downloadUrl(Yii::$app->params['qiniu_url_videos'], $url . '?vframe/jpg/offset/' . $second);
        return $link;
        // echo '<img src='.$link.'>';
    }

    /** 获取视频元信息(视频时长) */
    public static function videoTime($url)
    {
        $link = self::downloadUrl(Yii::$app->params['qiniu_url_videos'], $url . '?avinfo');
        $jsonStr2 = Helper::Post($link, 1);
        $arr = json_decode($jsonStr2, true);
        $time = ArrayHelper::getValue($arr, "streams.0.duration");
        return $time;
    }

    public function deleteFile($url)
    {
        require_once '../../vendor/qiniu/php-sdk/autoload.php';
        $auth = new Auth(Yii::$app->params['qiniu_access_key'], Yii::$app->params['qiniu_secret_key']);
        $bkt = Yii::$app->request->get('bkt') ?: Yii::$app->params['qiniu_bucket_videos'];
        $bucketMgr = new BucketManager($auth);
        $key = $url;
        $err = $bucketMgr->delete($bkt, $key);
        if ($err !== null) {
            return $err;
        } else {
            return 1;
        }
    }

    /**视频操作 */
    public static function videoPfop($url)
    {
        require_once '../../vendor/qiniu/php-sdk/autoload.php';
        $auth = new Auth(Yii::$app->params['qiniu_access_key'], Yii::$app->params['qiniu_secret_key']);
        $bkt = Yii::$app->request->get('bkt') ?: Yii::$app->params['qiniu_bucket_videos'];

        //要转码的文件所在的空间和文件名
        $bucket = Yii::$app->params['qiniu_bucket_videos'];
        $key = $url;

        //转码时使用的队列名称
        $pipeline = 'uu_oo';
        $pfop = new PersistentFop($auth, $bucket, $pipeline);

        //要进行转码的转码操作
        $fops = "avthumb/mp4/s/640x360/vb/1.25m";
        list($id, $err) = $pfop->execute($key, $fops);
        //查询转码的进度和状态
        list($ret, $err) = $pfop->status($id);

        if ($err != null) {
            return $url;
        } else {
            if ($ret['items'][0]['returnOld'] != 1) {
                return null;
            }
            return $ret['items'][0]['key'];
        }
    }

    /** 上传base64编码的图片 */
    public static function requestByCurl($remote_server, $post_string)
    {
        require_once '../../vendor/qiniu/php-sdk/autoload.php';
        $auth = new Auth(Yii::$app->params['qiniu_access_key'], Yii::$app->params['qiniu_secret_key']);
        $bucket = Yii::$app->request->get('$bucket') ?: Yii::$app->params['qiniu_bucket_images'];
        $upToken = $auth->uploadToken($bucket, null, 3600);//获取上传所需的token

        $headers = array();
        $headers[] = 'Content-Type:image/png';
        $headers[] = 'Authorization:UpToken ' . $upToken;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        //curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }


}