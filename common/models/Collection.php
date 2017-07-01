<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

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
            [['user_id', 'type_id', 'type', 'created_at', 'updated_at', 'status'], 'integer'],
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

    /** 判断该用户是否已经收藏 */
    public function collectionFlag($params)
    {
        $query = Collection::find()
            ->where(["type_id" => ArrayHelper::getValue($params, 'type_id')])
            ->andWhere(["type" => ArrayHelper::getValue($params, 'type')])
            ->andWhere(["user_id" => Yii::$app->user->id])
            ->andFilterWhere(["status" => ArrayHelper::getValue($params, 'status')]);
        return $query->one();
    }

    /** 收藏  */
    public function create($params)
    {
        $collection = new Collection();
        $collection->user_id = Yii::$app->user->id;
        $collection->type = ArrayHelper::getValue($params, 'type');
        $collection->type_id = ArrayHelper::getValue($params, 'type_id');
        $collection->status = self::STATUS_ENABLE;
        $collection->created_at = time();
        $collection->updated_at = time();
        if ($collection->save()) {
            return true;
        }
    }

    /** 获取收藏数量 */
    public static function collectionCount($type, $type_id)
    {
        return self::find()->where(['type' => $type])->andWhere(['type_id' => $type_id])->count();
    }
}
