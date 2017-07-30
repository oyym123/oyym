<?php

namespace common\models;

use yii\base\Exception;
use common\helpers\QiniuHelper;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_info".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $profit
 * @property string $intro
 * @property integer $like_count
 * @property integer $province
 * @property integer $city
 * @property integer $area
 * @property integer $created_at
 * @property integer $attentions
 * @property integer $updated_at
 * @property integer $sold_products
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
            [['user_id', 'name'], 'required'],
            [['user_id', 'like_count', 'created_at', 'updated_at', 'province', 'city', 'area'], 'integer'],
            [['profit'], 'number'],
            [['name', 'intro'], 'string', 'max' => 255],
        ];
    }

    public function phoneSystem()
    {
        return ArrayHelper::getValue(Base::$phoneSystem, $this->phone_system, '未知');
    }

    /** 取得用户所在地区 */
    public function getUserArea()
    {
        return $this->hasOne(City::className(), ['id' => 'province']);
    }

    public function getPublishNumber($userId, $type)
    {
        return Product::find()->where(['created_by' => $userId])->count();
    }


    /** 用户头像地址 */
    public function photoUrl($userId)
    {
        $image = Image::findOne(['type' => Image::TYPE_USER_PHOTO, 'type_id' => $userId, 'status' => Base::STATUS_ENABLE]);
        return $image ? QiniuHelper::downloadImageUrl(Yii::$app->params['qiniu_url_images'], $image->url) : Yii::$app->params['defaultPhoto'];
    }

    /** 用户头像ID */
    public function photoId($userId)
    {
        $image = Image::findOne(['type' => Image::TYPE_USER_PHOTO, 'type_id' => $userId, 'status' => Base::STATUS_ENABLE]);
        return $image ? $image->id : 0;
    }

    /** 用户关注数量保存 */
    public static function attention($id, $count)
    {
        $model = UserInfo::findModel($id);
        $model->attentions = $count;
        if (!$model->save()) {
            throw new Exception('关注失败!');
        }
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

    public static function findModel($id)
    {
        if (($model = UserInfo::findOne(['user_id' => $id]))) {
            return $model;
        } else {
            throw new Exception('用户不存在!');
        }
    }
}
