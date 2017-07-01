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
    const TYPE_USER_PHOTO = 1; //用户头像
    const TYPE_PRODUCT = 2; //产品图片
    const TYPE_COMMENTS = 3; //评论图片

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
            [['type', 'type_id', 'created_at', 'updated_at'], 'required'],
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
    public function getImages($params)
    {
        $images = self::find()
            ->where(['type_id' => ArrayHelper::getValue($params, 'type_id'),
                'type' => ArrayHelper::getValue($params, 'type'),
                'status' => self::STATUS_ENABLE])->orderBy('sort desc')->all();

        $data = [];
        foreach ($images as $key => $image) {
            if ($key < $params['limit']) {  //设置图片下放最大数量
                if ($image->url) {
                    $data[] = [
                        'url' => QiniuHelper::downloadUrl(Yii::$app->params['qiniu_url_images'], $image->url),
                        'name' => $image->name ?: ''
                    ];
                }
            }
        }
        return $data;
    }

    /** 获取单张图片 */
    public function getOneImage($params)
    {
        $image = self::find()
            ->where(['type_id' => ArrayHelper::getValue($params, 'type_id'),
                'type' => ArrayHelper::getValue($params, 'type'),
                'status' => self::STATUS_ENABLE])->orderBy('sort desc')->one();

        return [
            'url' => QiniuHelper::downloadUrl(Yii::$app->params['qiniu_url_images'], $image->url),
            'name' => $image->name ?: ''
        ];
    }

    /** 设置图片 */
    public function setImage($params)
    {
        $image = new Image();
        $image->name = ArrayHelper::getValue($params, 'name');
        $image->type = ArrayHelper::getValue($params, 'type');
        $image->type_id = ArrayHelper::getValue($params, 'type_id');
        $image->url = ArrayHelper::getValue($params, 'url');
        $image->size_type = ArrayHelper::getValue($params, 'size_type');
        $image->status = ArrayHelper::getValue($params, 'status');
        $image->created_at = time();
        $image->updated_at = time();
        if (!$image->save()) {
            throw new Exception('图片保存失败!');
        }
    }
}
