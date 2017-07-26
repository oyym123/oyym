<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property integer $upid
 * @property string $name
 * @property integer $level
 */
class Base extends \yii\db\ActiveRecord
{
    public $offset = 0; // 分页
    public $limit = 10; // 分页
    public $userEntity = null; // 用户实体
    public $psize = 20;
    public $params = [];

    Use BaseData;

    const STATUS_DISABLE = 0;   // 无效
    const STATUS_ENABLE = 1;    // 有效

    public function status()
    {
        return [
            self::STATUS_ENABLE => Yii::t('app', '有效'),
            self::STATUS_DISABLE => Yii::t('app', '无效'),
        ];
    }

    public function nowStatus()
    {
        return $this->status()[$this->status];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => function () {
                    return time();
                },
            ],
        ];

    }

    public function createdAt($format = 'Y-m-d H:i:s')
    {
        return date($format, $this->created_at);
    }

    public function updatedAt($format = 'Y-m-d H:i:s')
    {
        return date($format, $this->updated_at);
    }

    public function settlementDate($format = 'Y-m-d H:i:s')
    {
        return date($format, $this->settlement_date);
    }

    /** 时间转换字符串 */
    public function date2String($date, $format = 'Y-m-d')
    {
        return date($format, $date);
    }

    /** 时间转换数值 */
    public function date2Int($attribute, $param)
    {
        !empty($this->$attribute) && $this->$attribute = strtotime($this->$attribute);
    }


    /**
     * Name: replaceContentsImage
     * Desc: 替换图片路径
     * User: lixinxin <ulee@fangdazhongxin.com>
     * Date: 2017-03-10
     */
    public function replaceContentsImage()
    {
        if (!empty($this->contents)) {
            $this->contents = str_replace('/ueditor/php/upload/image/', Yii::$app->params['adminHost'] . 'ueditor/php/upload/image/', $this->contents);
        }
    }
}
