<?php

namespace common\models;

use common\helpers\QiniuHelper;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "video".
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
class Video extends Base
{
    const TYPE_PRODUCT = 1;     //产品视频

    const SIZE_SMOOTH = 1;      //流畅(480P)
    const SIZE_HD = 2;          //高清(720P)
    const SIZE_ULTRA_CLEAR = 3; //超清(1080P)

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'type_id', 'user_id', 'url'], 'required'],
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
            'name' => '视频名称',
            'url' => '视频地址',
            'type' => '类型',
            'type_id' => '类型ID',
            'size_type' => '视频分辨率（大小）',
            'user_id' => '用户ID',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /** 获取所有视频类型 */
    public static function videoType()
    {
        return [
            self::TYPE_PRODUCT => '产品视频',
        ];
    }

    /** 获取视频类型 */
    public function getType()
    {
        return ArrayHelper::getValue(self::videoType(), $this->type);
    }

    /** 检验是否应该上传 */
    public static function deleteVideos($params)
    {
        $videoId = self::findAll(['user_id' => Yii::$app->user->id,
            'status' => Video::STATUS_ENABLE,
            'type' => $params['video_type'],
            'type_id' => $params['type_id']]);
        foreach ($videoId as $item) {
            if (!in_array($item->url, $params['video_urls'])) {
                $item->status = Video::STATUS_DISABLE;
                if (!$item->save()) {
                    throw new Exception('保存视频验证出错！');
                }
            }
        }
    }

    /** 获取所有视频 */
    public static function getVideos($params)
    {
        $videos = self::find()
            ->where(['type_id' => ArrayHelper::getValue($params, 'type_id'),
                'type' => ArrayHelper::getValue($params, 'type'),
                'status' => self::STATUS_ENABLE])->orderBy('sort desc')
            ->offset(ArrayHelper::getValue($params, 'offset'))
            ->limit(ArrayHelper::getValue($params, 'limit'))->all();
        $data = [];
        foreach ($videos as $video) {
            if ($video->url) {
                $data[] = [
                    'url' => QiniuHelper::downloadVideoUrl(Yii::$app->params['qiniu_url_videos'], $video->url),
                    'name' => $video->name ?: '',
                    'key' => $video->url
                ];
            }
        }
        return $data;
    }

    /** 设置视频 */
    public static function setVideo($params)
    {
        $video = self::findOne(['url' => $params['url']]) ?: new video();
        $video->name = ArrayHelper::getValue($params, 'name');
        $video->type = ArrayHelper::getValue($params, 'type');
        $video->type_id = ArrayHelper::getValue($params, 'type_id');
        $video->url = ArrayHelper::getValue($params, 'url');
        $video->size_type = ArrayHelper::getValue($params, 'size_type');
        $video->status = ArrayHelper::getValue($params, 'status');
        $video->sort = ArrayHelper::getValue($params, 'sort');
        $video->user_id = Yii::$app->user->id;
        if (!$video->save()) {
            throw new Exception('视频保存失败!');
        }
    }

    /** 用于限制视频上传数量 */
    public static function videoLimit($params)
    {
        return self::find()->where(['type_id' => $params['type_id'],
            'user_id' => Yii::$app->user->id, 'type' => $params['type']])->count();
    }

    /** 删除视频 */
    public static function deleteVideo($params)
    {
        $model = self::find()->where(['user_id' => Yii::$app->user->id, 'id' => $params['id']])->one();
        $model->status = self::STATUS_DISABLE;
        if (!$model->save()) {
            throw new Exception('删除视频失败!');
        }
    }
}
