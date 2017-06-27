<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type_id
 * @property integer $type
 * @property string $contents
 * @property integer $star
 * @property integer $sort
 * @property string $child_ids
 * @property integer $parent_id
 * @property integer $like_count
 * @property integer $comment_count
 * @property integer $comment_line_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Comments extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type_id', 'type', 'star', 'sort', 'status', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'type_id', 'type', 'star', 'sort', 'parent_id', 'like_count', 'comment_count', 'comment_line_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['contents'], 'string'],
            [['child_ids'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'type_id' => '类型ID',
            'type' => '类型',
            'contents' => '内容',
            'star' => '星级',
            'sort' => '排序',
            'child_ids' => '评论子类ID',
            'parent_id' => '评论父类ID',
            'like_count' => '点赞人数',
            'comment_count' => '评论人数',
            'comment_line_id' => '多级评论标记',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
