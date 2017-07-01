<?php
namespace backend\components;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class WebController extends Controller
{
    public function init()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $userRule = [
            '13161057904' => '*', // 欧阳裕民
            '18606615070' => '*', // 新哥
        ];
//        echo Yii::$app->user->identity->username;exit;
        if (empty($userRule[Yii::$app->user->identity->username])) {
            throw new BadRequestHttpException('权限限制');
        }

        if ($userRule[Yii::$app->user->identity->username] != '*') {
            if (!in_array(Yii::$app->requestedRoute, $userRule[Yii::$app->user->identity->username])) {
                throw new BadRequestHttpException('权限限制');
            }
        }

//        print_r(Yii::$app->runtimePath . '/logs/request_' . Yii::$app->getUser()->id .'.log');exit;
//        file_put_contents('/tmp/request_' . Yii::$app->getUser()->id .
//      '.log', date('Y-m-d H:i:s') . "\t" . $_SERVER['REQUEST_URI'] . "\t" . json_encode($_REQUEST));
    }

//    public function beforeAction(Controller $action)
    public function beforeAction($action)
    {
        \Yii::trace($action->id, __CLASS__ . "::" . __FUNCTION__);
        if (!parent::beforeAction($action)) return false;
        return true;
    }

//    public function afterAction(Controller $action, $result)
    public function afterAction($action, $result)
    {
        \Yii::trace($action->id, __CLASS__ . "::" . __FUNCTION__);
        parent::afterAction($action, $result);
        return $result;
    }
}