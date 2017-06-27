<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "collection".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type_id
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 */
class Collection extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type_id', 'type', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'type_id', 'type', 'created_at', 'updated_at'], 'integer'],
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
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
