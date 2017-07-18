<?php
namespace console\controllers;

use backend\helpers\MyHelper;
use backend\helpers\PinyinHelper;
use common\models\Advice;
use common\models\Article;
use common\models\BaseData;
use common\models\City;
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
    public function actionProgress()
    {
        $products = Product::findAll(['model' => Product::MODEL_TIME, 'status' => Product::STATUS_IN_PROGRESS]);
        foreach ($products as $product) {
            $product->progress = $product->getProgress();
            if (!$product->save()) {
                echo ($product->id) . '保存出错!-------' . date('Y-m-d H:i:s') . "\r\n";
            }
        }
        echo '产品进度更新成功!-------' . date('Y-m-d H:i:s') . "\r\n";
    }
}
