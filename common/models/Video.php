<?php

namespace common\models;

use Yii;

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
}
