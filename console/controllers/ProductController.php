<?php
namespace console\controllers;

use backend\helpers\MyHelper;
use backend\helpers\PinyinHelper;
use common\models\Advice;
use common\models\Article;
use common\models\BaseData;
use common\models\City;
use common\models\Crontab;
use common\models\Download;
use common\models\College;
use common\models\News;
use common\models\School;
use common\models\SchoolAttributeConfig;
use common\models\SchoolExcel;
use common\models\User;
use common\models\UserBenefit;
use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\debug\models\search\Base;
use yii\helpers\ArrayHelper;
use common\models\Product;
use common\models\Comments;
use yii\db\Query;

/**
 * User: lixinxin<lixinxinlgm@163.com>
 * Class ProductController
 * @package app\console
 */
class ProductController extends Controller
{

    public function actionInit()
    {
        $this->actionCity();
        echo "city ok \n";
    }


    public function actionInitUser()
    {

    }

    /** 测试 */
    public function actionCrontab()
    {
        date_default_timezone_set('PRC');
        echo date('Y-m-d H:i:s') . "\r\n";
    }

    /** 定时更新时间模式下产品进度值 */
    public function actionProgress($type)
    {
        $crontab = new Crontab();
        $crontab->params = '';
        $crontab->type = $type;
        $crontab->exec_max_count = 1000;
        $crontab->exec_start_time = time() + 30;
        $crontab->status = Crontab::WAIT;
        if (!$crontab->save()) {
            echo '保存进度失败';
        }
    }

}
