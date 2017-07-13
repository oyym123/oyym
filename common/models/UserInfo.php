<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_info".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $profit
 * @property integer $like_count
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserInfo extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'like_count', 'created_at', 'updated_at', 'province', 'city', 'area'], 'integer'],
            [['profit'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function phoneSystem()
    {
        return ArrayHelper::getValue(Base::$phoneSystem, $this->phone_system, '未知');
    }

    /** 用户头像地址 */
    public function photoUrl()
    {
        return empty($this->photo) ? Yii::$app->params['defaultPhoto'] : Yii::$app->params['qiniu_url_images'] . $this->photo;
    }

    /** 取得用户所在地区 */
    public function getUserArea()
    {
        return $this->hasOne(City::className(), ['id' => 'province']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'name' => '名称',
            'profit' => '收益',
            'province' => '省',
            'city' => '市',
            'area' => '地区',
            'like_count' => '点赞数',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
