<?php
/**
 * Created by PhpStorm.
 * User: OYYM
 * Date: 2017/7/5
 * Time: 12:55
 */
namespace frontend\controllers;

use Codeception\Util\Xml;
use frontend\components\WebController;
use Yii;

class SearchController extends WebController
{
    /** 抓取http://www.btwhat.net的数据 */
    public function searchInfo($keyWords)
    {
        $url = 'http://www.btwhat.net/rss/' . $keyWords . '.xml';
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


}