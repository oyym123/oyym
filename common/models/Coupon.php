<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "coupon".
 *
 * @property integer $id
 * @property integer $type
 * @property string $title
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 */
class Coupon extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'title', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'required'],
            [['type', 'start_time', 'end_time', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
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
            'type' => '类型',
            'title' => '名称',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'created_by' => '创建者',
            'updated_by' => '修改者',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
