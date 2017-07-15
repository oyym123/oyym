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
    const TYPE_PRODUCT = 1; //商品


    const COLLECT = 1;
    const NOT_COLLECT = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collection';
    }

    public static function getType()
    {
        return [
            self::TYPE_PRODUCT => '宝贝商品',
        ];
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

    /** 判断所传类型是否存在 */
    public function checkType($key)
    {
        if (!array_key_exists($key, self::getType())) {
            throw new Exception('该收藏类型不存在!');
        }
    }

    /** 保存对应表中收藏数量 */
    public function saveTypeNumbers($type, $type_id)
    {
        switch ($type) {
            case Collection::TYPE_PRODUCT:
                return Product::collection($type_id, self::collectionCount($type, $type_id));
        }
    }

    /** 判断该用户是否已经收藏 */
    public static function collectionFlag($params)
    {
        $query = Collection::find()
            ->where(["type_id" => ArrayHelper::getValue($params, 'type_id')])
            ->andWhere(["type" => ArrayHelper::getValue($params, 'type')])
            ->andWhere(["user_id" => Yii::$app->user->id])
            ->andFilterWhere(["status" => ArrayHelper::getValue($params, 'status')]);
        return $query->one() ? Collection::COLLECT : Collection::NOT_COLLECT;
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
        return self::find()->where(['type' => $type])->andWhere(['type_id' => $type_id, 'status' => self::STATUS_ENABLE])->count();
    }
}
