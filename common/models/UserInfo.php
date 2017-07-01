<?php

namespace common\models;

use Yii;

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
