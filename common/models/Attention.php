<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "attention".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type_id
 * @property integer $type
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Attention extends Base
{
    const TYPE_USER = 1; // 用户
    const PRODUCT_TYPE = 2; //商品

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attention';
    }

    public static function getType()
    {
        return [
            self::TYPE_USER => '宝贝商品',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type_id', 'type'], 'required'],
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
            throw new Exception('该关注类型不存在!');
        }
    }

    /** 保存对应表中关注数量 */
    public function saveTypeNumbers($type, $type_id)
    {
        switch ($type) {
            case Attention::TYPE_USER:
                return UserInfo::attention($type_id, self::attentionCount($type, $type_id));
        }
    }

    /** 判断该用户是否已经关注 */
    public static function attentionFlag($params)
    {
        $query = Attention::find()
            ->where(["type_id" => ArrayHelper::getValue($params, 'type_id')])
            ->andWhere(["type" => ArrayHelper::getValue($params, 'type')])
            ->andWhere(["user_id" => Yii::$app->user->id])
            ->andFilterWhere(["status" => ArrayHelper::getValue($params, 'status')]);
        return $query->one();
    }

    /** 取消关注所有宝贝分类 */
    public function cancelProductType()
    {
        Attention::deleteAll([
            "type" => Attention::PRODUCT_TYPE,
            "user_id" => Yii::$app->user->id,
            "status" => Attention::STATUS_ENABLE
        ]);
    }

    /** 关注  */
    public function create($params)
    {
        $attention = new Attention();
        $attention->user_id = Yii::$app->user->id;
        $attention->type = ArrayHelper::getValue($params, 'type');
        $attention->type_id = ArrayHelper::getValue($params, 'type_id');
        $attention->status = self::STATUS_ENABLE;
        if ($attention->save()) {
            return true;
        }
    }

    /** 获取关注数量 */
    public static function attentionCount($type, $type_id)
    {
        return self::find()->where(['type' => $type])->andWhere(['type_id' => $type_id, 'status' => self::STATUS_ENABLE])->count();
    }
}