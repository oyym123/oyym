<?php
namespace common\helpers;

use yii\helpers\BaseArrayHelper;

class Sphinx extends BaseArrayHelper
{
    public static function actionSearch($author)
    {
        $sphinx = new \SphinxClient();
        $sphinx->setServer('127.0.0.1', 9312);
        $res = $sphinx->Query($author, '*');
        return $res;
    }
}