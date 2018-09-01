<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/5
 * Time: 12:55
 */
namespace frontend\controllers;

use app\helpers\Helper;
use Codeception\Util\Xml;
use frontend\components\WebController;
use Yii;

class SearchController extends WebController
{
    /** 抓取http://www.btwhat.net的数据 */
    public function searchInfo($keyWords)
    {
        $url = 'https://www.btbook.us/rss/' . $keyWords . '.xml';
        header('Content-Type:text/html;charset= UTF-8');
        $xmldata = "";
        $fp = fopen($url, "r");
        while (!feof($fp)) {
            $xmldata .= fgets($fp, 4096);
        }
        fclose($fp);
        $xml_object = NULL;
        $xml_object = simplexml_load_file($url);
        $xml_json = json_encode($xml_object);
        return json_decode($xml_json, true);
    }

    public function actionIndex()
    {
        if (Yii::$app->request->get('key_words')) {
            $this->layout = 'blank';
            return $this->render('list', [
                'searchInfo' => $this->searchInfo(Yii::$app->request->get('key_words')),
            ]);
        }
        $this->layout = 'blank';
        return $this->render('index', [
            'data' => '',
        ]);
    }

    public function actionUrl()
    {
        set_time_limit(0);
        $url = Yii::$app->request->get('url');
        $output = Helper::getInfo('http://www.btbook.us/wiki/' . $url);
        preg_match_all("/<div class=\"panel-body\"><a href=\"(.*)(?)\"/", $output, $name);
        $res = $name[1][0];
        echo '<a  id="clickMe" href="' . $res . '" ></a>';
        echo '<script type="text/javascript">
            setTimeout(function() {
                // IE
                if(document.all) {
                    document.getElementById("clickMe").click();
                }
                // 其它浏览器
                else {
                    var e = document.createEvent("MouseEvents");
                    e.initEvent("click", true, true);
                    document.getElementById("clickMe").dispatchEvent(e);
                }
            }, 10);
            </script>';
    }
}