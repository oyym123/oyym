<?php
namespace frontend\components;

use app\helpers\Helper;
use common\models\User;
use common\models\VisitAmount;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class WebController extends Controller
{

    public $enableCsrfValidation = false;
    public $offset = 0;
    public $skip = 0;
    public $psize = 10;
    public $userId = 0;
    public $userIdent = 0;
    public $token;

    function init()
    {
        Yii::$app->request->get('offset') && $this->offset = Yii::$app->request->get('offset');
        Yii::$app->request->get('skip') && $this->skip = Yii::$app->request->get('skip');
        Yii::$app->request->get('psize') && $this->psize = Yii::$app->request->get('psize');
        $this->token = empty($_SERVER['HTTP_KY_TOKEN']) ? '' : $_SERVER['HTTP_KY_TOKEN'];
        $this->token && $this->userId = Yii::$app->redis->hget('token', $this->token);

        if ($this->userId) {
            Yii::$app->user->identity = $this->getApiUser();
        }

        VisitAmount::createVisit();
        if (isset($_REQUEST['env_dev'])) {
            \common\helpers\Helper::writeLog($_GET);
            \common\helpers\Helper::writeLog($_POST);
        }

//        $session = Yii::$app->session;

//        if (empty($session->get('user_id')) && in_array(explode('/', Yii::$app->requestedRoute)[0], Yii::$app->params['needLogin'])) {
        // self::showMsg('需要登录', -1);
//        }

        // 定义手机类型
        // define('PHONE_SYSTEM', $this->getAppType());
    }

//    public function beforeAction(Controller $action)
    public function beforeAction($action)
    {
        \Yii::trace($action->id, __CLASS__ . "::" . __FUNCTION__);
        if (!parent::beforeAction($action)) return false;
        return true;
    }


    public function afterAction($action, $result)
    {
        \Yii::trace($action->id, __CLASS__ . "::" . __FUNCTION__);
        parent::afterAction($action, $result);
        return $result;
    }

    public function needLogin()
    {
        if ($this->token) {
            self::showMsg('登录已过期', -100);
        }

        self::showMsg('需要登录', -100);
    }

    public function needHtmlLogin()
    {
        Yii::$app->session->setFlash('error', '需要您先登录，才能进行操作哦O(∩_∩)O~~');
        echo '<meta http-equiv="refresh" content="0.01;URL=../index.php">';
        exit;
    }

    /** 设置提示消息 */
    public static function setHint($contents)
    {
        Yii::$app->session->setFlash('error', $contents);
        echo '<script type="text/javascript">
                    window.history.back(-1);
                  </script>';
        exit;
    }

    /**
     * 解析并送出JSON
     * 200101
     * @param  array $res 资源数组，如果是一个字符串则当成错误信息输出
     * @param  int $state 状态值，默认为0
     * @param  int $msg 是否直接输出,1为返回值
     * @return array
     **/
    public function showMsg($res, $code = 0, $msg = '')
    {
        //header("Content-type: application/json; charset=utf-8");

        empty($res) && $res = '';
        // 构造数据
        $item = array('code' => $code, 'msg' => $msg, 'version' => 1, 'res' => null);

        if (is_array($res) && !empty($res)) {
            $item['res'] = $this->int2String($res); // 强制转换为string类型下放
        } elseif (is_string($res)) {
            $item['msg'] = $res;
        }

        // 是否需要送出get
        if (isset($_GET['isget']) && $_GET['isget'] == 1) {
            $item['pars'] = !empty($_GET) ? $_GET : null;
        }

        if ((isset($_GET['debug']) && $_GET['debug'] == '1') || strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
            echo "<pre>";
            print_r($_REQUEST);
            print_r($item);
        } else {
            //编码
            $item = json_encode($item);

            // 是否为jsonp访问
            if (isset($_GET['callback']) && !empty($_GET['callback'])) {
                $item = "{$_GET['callback']}($item)";
            }

            // 送出信息
            echo "{$item}";
        }

        exit;
    }

    /**
     * Name: changes
     * Desc:
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-00-00
     * @param $arr
     * @return mixed
     */
    function int2String($arr)
    {
        foreach ($arr as $k => $v) {
            if (is_int($v)) {
                $arr[$k] = (string)$v;
            } else if (is_array($v)) { //若为数组，则再转义.
                $arr[$k] = $this->int2String($v);
            }
        }
        return $arr;
    }

    public static function post($url, $post_data)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据

        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        $output = curl_exec($ch);

        curl_close($ch);
        //打印获得的数据

        return $output;
    }


    //接口-当前应用类型
    public static function getAppType()
    {
        $tmp = 0;
        isset($_SERVER['HTTP_KY_APPTYPE']) && $tmp = (int)$_SERVER['HTTP_KY_APPTYPE'];

        return $tmp <= 0 ? 2 : $tmp;
    }

    /** 根据user-agent取手机类型 */
    public static function getAppTypeByUa()
    {
        $tmp = 0;
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
            $tmp = 1;
        } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
            $tmp = 2;
        }

        return $tmp;
    }

    //接口-取版本号
    public static function getAppVersion()
    {
        $tmp = 0;
        if (static::getAppType() == 1) {
            // ios
            isset($_SERVER['HTTP_KY_VERSION']) && $tmp = $_SERVER['HTTP_KY_VERSION'];
        } elseif (static::getAppType() == 2) {
            // Android
            $tmp = isset($_SERVER['HTTP_KY_VERSION']) ? $_SERVER['HTTP_KY_VERSION'] : '3.1.0';
        }

        return $tmp <= 0 ? 0 : $tmp;
    }

    /** 根据token取用户 */
    public function getApiUser()
    {
        if (!$this->token || !($userId = Yii::$app->redis->hget('token', $this->token))) {
            return null;
        }

        return User::findOne(['id' => $userId]);
    }

    /** 取缓存数据 */
    public static function getCache()
    {
        $redis = Yii::$app->redis;
        $redis->select(1);
        if (!empty($_SERVER['REQUEST_URI'])) {
            $val = $redis->hget('cache', md5($_SERVER['REQUEST_URI']));
            if ($val) {
                echo $val;
                exit;
            }
        }
    }

    /** 设置缓存数据 */
    public static function setCache($key, $val)
    {
        $redis = Yii::$app->redis;
        $redis->select(1);
        $redis->hset('cache', $key, $val);
    }

    /** 删除缓存数据 */
    public static function delCache($key)
    {
        $redis = Yii::$app->redis;
        $redis->select(1);
        $redis->hdel('cache', $key);
    }

    public static function isAndroid()
    {
        return self::getAppType() == 2;
    }

    public static function isIos()
    {
        return self::getAppType() == 1;
    }

    /**
     * Name: ajax
     * Desc: ajax请求返回标准数据
     * User: lixinxin <lixinxinlgm@163.com>
     * Date: 2016-06-28
     * @param $code 0 | 110000
     * @param string $msg 消息字符串
     * @param array $data 数据
     */
    public static function ajax($code = 0, $msg = '', $data = [])
    {
        echo Json::encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }

    /**
     * Name: isWaitCheckInIos
     * Desc: 正在审核的ios版本
     * User: lixinxin <ulee@fangdazhongxin.com>
     * Date: 2017-02-14
     * @return bool
     */
    public function isWaitCheckInIos()
    {
        if ($this->isIos()) {
            $newVersion = ArrayHelper::getValue(Yii::$app->params['lastVersion'], '1.version', '');
            $newVersion = explode('.', $newVersion);
            $version = explode('.', self::getAppVersion()); // 客户端返回版本

            foreach ($newVersion as $key => $val) {
                if (isset($version[$key])) {
                    if ($val < $version[$key]) {
                        // 设置版本 < 客户端最新版本, 不显示付费视频
                        return true;
                    }
                }
            }
        }

        return false;
    }


    /**
     * Name: goEasy
     * Desc: 消息推送
     * User: ouyangyumin <ouyangyumin@zgzzzs.com>
     * Date: 2017-00-00
     * @param $randnumber
     * @param $content
     */
    public static function goEasy($randnumber, $content)
    {
        $post_data = [
            'appkey' => Yii::$app->params['goEasy'],
            'channel' => $randnumber,
            'content' => $content
        ];
        WebController::post('http://goeasy.io/goeasy/publish', $post_data);
    }


}
