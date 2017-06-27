<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type_id
 * @property string $title
 * @property string $price
 * @property string $original_price
 * @property integer $unit
 * @property string $contents
 * @property integer $watches
 * @property integer $comments
 * @property string $freight
 * @property integer $sort
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Product extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type_id', 'title', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'type_id', 'unit', 'watches', 'comments', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['price', 'original_price', 'freight'], 'number'],
            [['contents'], 'string'],
            [['title'], 'string', 'max' => 255],
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
            'type_id' => '产品类型',
            'title' => '名称',
            'price' => '价格',
            'original_price' => '原价',
            'unit' => '单位',
            'contents' => '内容',
            'watches' => '观看人数',
            'comments' => '评论人数',
            'freight' => '运费',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
