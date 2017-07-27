<?php
namespace console\controllers;

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
use common\helpers\PinyinHelper;

/**
 * User: lixinxin<lixinxinlgm@163.com>
 * Class HelloController
 * @package app\console
 */
class HelloController extends Controller
{

    public function actionInit()
    {
        $this->actionCity();
        echo "city ok \n";
    }

    public function actionInitUser()
    {
        $user = User::find()->where(['id' => 1])->one();

        if ($user) {
            $user = new User();
            $user->id = 1;
            $user->username = '18606615070';
            $user->email = 'ulee@fangdazhongxin.com';
            $user->setPassword('li123456');;
            $user->generateAuthKey();

            $user->save();

            if (!empty($user->getFirstErrors())) {
                print_r($user->getFirstErrors());
            }

            $user = new User();
            $user->id = 3;
            $user->username = '13161057904';
            $user->email = 'oyym@fangdazhongxin.com';
            $user->setPassword('li123456');;
            $user->generateAuthKey();

            $user->save();

            $user = new User();
            $user->id = 6;
            $user->username = '17610068627';
            $user->email = 'hemengjie@fangdazhongxin.com';
            $user->setPassword('li123456');;
            $user->generateAuthKey();

            $user->save();

            $user = new User();
            $user->id = 9;
            $user->username = '15210771883';
            $user->email = 'lishanhong@fangdazhongxin.com';
            $user->setPassword('li123456');;
            $user->generateAuthKey();

            $user->save();
        }
    }

    public function actionInitToken()
    {
        Yii::$app->redis->hset('token', 1, 1);
        Yii::$app->redis->hset('token', 2, 3);
        Yii::$app->redis->hset('token', 3, 6);
        Yii::$app->redis->hset('token', 4, 9);
    }

    /**
     * Name: actionIndex
     * Desc: 导入城市
     * User: lixinxin <lixinxinlgm@163.com>
     * Date: 2017-07-24
     * @return bool
     */
    public function actionImportCity()
    {
        $sql = require(__DIR__ . '/../files/city.php');

        Yii::$app->getDb()->createCommand($sql)->execute();

        echo "ok \n";
    }

    /** 省市拼音排序 */
    public function actionSetCityFirstChar()
    {
        foreach (City::find()->each(10) as $item) {
            $item->sort_by_pinyin = substr(PinyinHelper::getPinyin($item->name), 0, 30);
            $item->save();
            if ($item->getErrors()) {
                print_r($item->getErrors());
                exit;
            }
            echo "$item->name \n";
        }
    }
}
