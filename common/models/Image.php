<?php

namespace common\models;

use common\helpers\QiniuHelper;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $type
 * @property integer $type_id
 * @property integer $size_type
 * @property integer $user_id
 * @property integer $sort
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Image extends Base
{
    const TYPE_USER_PHOTO = 1;  //用户头像
    const TYPE_PRODUCT = 2;     //产品图片
    const TYPE_COMMENTS = 3;    //评论图片

    const SIZE_SMALL = 1;       //小图(缩率图)
    const SIZE_MEDIUM = 2;      //中等图
    const SIZE_BIG = 3;         //大图(原图)

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'type_id', 'user_id'], 'required'],
            [['sort'], 'default', 'value' => 0],
            [['type', 'type_id', 'size_type', 'user_id', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'url' => '图片地址',
            'type' => '类型',
            'type_id' => '类型Id',
            'size_type' => '图片尺寸',
            'user_id' => '用户ID',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /** 获取所有图片类型 */
    public static function imageType()
    {
        return [
            self::TYPE_USER_PHOTO => '用户头像',
            self::TYPE_PRODUCT => '产品图片',
            self::TYPE_COMMENTS => '评论图片',
        ];
    }

    /** 获取图片类型 */
    public function getType()
    {
        return ArrayHelper::getValue(self::imageType(), $this->type);
    }

    /** 获取所有图片 */
    public static function getImages($params)
    {
        $images = self::find()
            ->where(['type_id' => ArrayHelper::getValue($params, 'type_id'),
                'type' => ArrayHelper::getValue($params, 'type'),
                'status' => self::STATUS_ENABLE])->orderBy('sort desc')
            ->offset(ArrayHelper::getValue($params, 'offset'))
            ->limit(ArrayHelper::getValue($params, 'limit'))->all();
        $data = [];
        foreach ($images as $image) {
            if ($image->url) {
                $data[] = [
                    'id' => $image->id,
                    'url' => QiniuHelper::downloadImageUrl(Yii::$app->params['qiniu_url_images'], $image->url),
                    'name' => $image->name ?: ''
                ];
            }
        }
        return $data;
    }

    /** 检验是否应该上传 */
    public static function deleteImages($params)
    {
        $imgId = self::findAll(['user_id' => Yii::$app->user->id,
            'status' => Image::STATUS_ENABLE,
            'type' => $params['img_type'],
            'type_id' => $params['type_id']]);
        foreach ($imgId as $item) {
            if (!in_array($item->url, $params['img_urls'])) {
                $item->status = Image::STATUS_DISABLE;
                if (!$item->save()) {
                    throw new Exception('保存图片验证出错！');
                }
            }
        }
    }

    /** 用于限制图片上传数量 */
    public static function imageLimit($params)
    {
        return self::find()->where(['type_id' => $params['type_id'],
            'user_id' => Yii::$app->user->id, 'type' => $params['type'], 'status' => self::STATUS_ENABLE])->count();
    }

    /** 设置图片 */
    public static function setImage($params)
    {
        $image = self::findOne(['url' => $params['url']]) ?: new Image();
        $image->name = ArrayHelper::getValue($params, 'name');
        $image->type = ArrayHelper::getValue($params, 'type');
        $image->type_id = ArrayHelper::getValue($params, 'type_id');
        $image->url = ArrayHelper::getValue($params, 'url');
        $image->size_type = ArrayHelper::getValue($params, 'size_type');
        $image->status = ArrayHelper::getValue($params, 'status');
        $image->sort = ArrayHelper::getValue($params, 'sort');
        $image->user_id = Yii::$app->user->id;
        if (!$image->save()) {
            throw new Exception('图片保存失败!');
        }
    }

    /** 删除图片 */
    public static function deleteImage($params)
    {
        $model = self::find()->where(['user_id' => Yii::$app->user->id, 'id' => $params['id']])->one();
        $model->status = self::STATUS_DISABLE;
        if (!$model->save()) {
            throw new Exception('删除图片失败!');
        }
    }

    public static function findModel($params)
    {
        if (($model = Image::findOne($params))) {
            return $model;
        } else {
            throw new Exception('图片不存在!');
        }
    }

}
