<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/21
 * Time: 21:17
 */

namespace console\controllers;

use Yii;
use common\models\Crontab;
use yii\console\Controller;

class CrontabController extends Controller
{
    /** 定时扫描crontab表，可以根据参数不同，定义不同时间间隔 */
    public function actionCrontab($type)
    {
        $crontab = new Crontab();
        $crontab->setRedis($type);
    }

    /** 定时开始执行redis缓存的数据，可以根据参数不同，定义不同时间间隔 */
    public function actionStartCrontab($type)
    {
        if (Yii::$app->redis->zcard($type . 'crontab_ids')) { //保证存在对应的倒计时数列
            $crontab = new Crontab();
            $crontab->start($type);
        }
    }
}