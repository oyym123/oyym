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
}
