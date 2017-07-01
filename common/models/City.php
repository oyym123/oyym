<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property integer $upid
 * @property string $name
 * @property integer $level
 */
class City extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['upid', 'name', 'level'], 'required'],
            [['upid', 'level'], 'integer'],
            [['name'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'upid' => Yii::t('app', '上级id'),
            'name' => Yii::t('app', '省/市名字'),
            'sort_by_pinyin' => Yii::t('app', '排序'),
            'level' => Yii::t('app', '0:国家 1:省 2:市'),
        ];
    }

    public static function province()
    {
        return ArrayHelper::map(static::findAll(['level' => 1]), 'id', 'name');
    }

    public static function cities($province)
    {
        return $province ? ArrayHelper::map(static::findAll(['upid' => $province]), 'id', 'name') : [];
    }


}
