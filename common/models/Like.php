<?php

namespace common\models;

use Symfony\Component\Console\Tests\Command\CommandTest;
use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "like".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type_id
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 */
class Like extends Base
{
    const TYPE_PRODUCT = 1; //产品类型
    const TYPE_COMMENT = 2; //评论类型

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'like';
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

    public static function getType()
    {
        return [
            self::TYPE_PRODUCT => '宝贝商品',
            self::TYPE_COMMENT => '评论',
        ];
    }

    /** 判断所传类型是否存在 */
    public function checkType($key)
    {
        if (!array_key_exists($key, self::getType())) {
            throw new Exception('该点赞类型不存在!');
        }
    }

    /** 保存对应表中点赞数量 */
    public function saveTypeNumbers($type, $type_id)
    {
        switch ($type) {
            case Like::TYPE_PRODUCT:
                return Product::like($type_id, self::likeCount($type, $type_id));
            case Like::TYPE_COMMENT:
                return Comments::like($type_id, self::likeCount($type, $type_id));
        }
    }

    /** 判断该用户是否已经点赞 */
    public static function likeFlag($params)
    {
        $query = self::find()
            ->where(["type_id" => ArrayHelper::getValue($params, 'type_id')])
            ->andWhere(["type" => ArrayHelper::getValue($params, 'type')])
            ->andWhere(["user_id" => Yii::$app->user->id])
            ->andFilterWhere(["status" => ArrayHelper::getValue($params, 'status')]);
        return $query->one();
    }

    /** 点赞  */
    public function create($params)
    {
        $like = new Like();
        $like->user_id = Yii::$app->user->id;
        $like->type = ArrayHelper::getValue($params, 'type');
        $like->type_id = ArrayHelper::getValue($params, 'type_id');
        $like->status = self::STATUS_ENABLE;
        $like->created_at = time();
        $like->updated_at = time();
        if ($like->save()) {
            return true;
        }
    }

    /** 获取点赞数量 */
    public static function likeCount($type, $type_id)
    {
        return self::find()->where(['type' => $type])->andWhere(['type_id' => $type_id, 'status' => self::STATUS_ENABLE])->count();
    }
}
