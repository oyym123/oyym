<?php

namespace common\models;

use Yii;
use common\helpers\Helper;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

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
    const TYPE_PRODUCT = 1; //产品评论留言
    const TYPE_PEOPLE = 2; //买家与卖家之间的评价

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
            [['user_id', 'type_id', 'type', 'star'], 'required'],
            [['user_id', 'type_id', 'type', 'star', 'sort', 'parent_id', 'like_count', 'comment_count', 'comment_line_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['contents'], 'string'],
            [['child_ids'], 'string', 'max' => 1000],
        ];
    }


    /** 获取用户名字 */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /** 获取用户头像 */
    public function getUserInfo()
    {
        return $this->hasOne(UserInfo::className(), ['user_id' => 'user_id']);
    }


    /** 判断该用户是否能评论 */
    public function commentFlag($params)
    {
        $query = Comments::find()
            ->where(["type_id" => ArrayHelper::getValue($params, 'product_id')])
            ->andWhere(["type" => ArrayHelper::getValue($params, 'type')])
            ->andFilterWhere(["user_id" => ArrayHelper::getValue($params, 'user_id')])
            ->andFilterWhere(["order_id" => ArrayHelper::getValue($params, 'order_id')]);
        return $query->one();
    }

    /** 获取话题评论 */
    public function commentProductSearch($params)
    {
        $query = Comments::find()
            ->where(["type_id" => ArrayHelper::getValue($params, 'product_id')])
            ->andWhere(["type" => ArrayHelper::getValue($params, 'type')])->andWhere(['parent_id' => 0]);
        $query->orderBy('sort desc, created_at desc');
        $query->offset(ArrayHelper::getValue($params, 'skip'));
        $query->limit(ArrayHelper::getValue($params, 'psize'));
        return $query->all();
    }

    /** 获取话题评论 */
    public static function getProduct($product_id, $skip, $psize)
    {
        $model = new Comments();
        $product = self::findProductModel($product_id);
        $params['skip'] = $skip;
        $params['psize'] = $psize;
        $params['product_id'] = $product_id;
        $params['type'] = Comments::TYPE_PRODUCT;
        $comments = $model->commentProductSearch($params);
        $datas['list'] = [];
        foreach ($comments as $comment) {
            $sonProduct = Comments::find()->where(['id' => explode(',', $comment->child_ids), 'status' => Comments::STATUS_ENABLE])->all();
            $data = [];
            foreach ($sonProduct as $item) {
                $data[] = [
                    'id' => $item->id,
                    'user_name' => $item->user ? $item->user->getName() : '',
                    'contents' => $item->contents
                ];
            }
            $datas['list'][] = [
                'id' => $comment->id,
                'user_photo' => $comment->userInfo->photoUrl($comment->user_id),
                'comment_count' => $comment->comment_count ?: 0,
                'comment_line_id' => $comment->comment_line_id,
                'like_count' => $comment->like_count,
                'user_name' => $comment->user ? $comment->user->getName() : '',
                'contents' => $comment->contents,
                'reply' => $data,
                'date' => Helper::tranTime($comment->created_at),
            ];
        }
        $datas['user_count'] = $product->comments;

        return $datas;
    }


    /** 获取话题评论 */
    public static function product($id)
    {

        $datas['list'] = [];
        $comment = Comments::findOne(['id' => $id, 'status' => self::STATUS_ENABLE]);
        if (!$comment) {
            return ['该评论不存在', -1];
        }
        $sonProduct = Comments::find()->where(['id' => explode(',', $comment->child_ids), 'status' => Comments::STATUS_ENABLE])->all();
        $data = [];
        foreach ($sonProduct as $item) {
            $data[] = [
                'id' => $item->id,
                'user_photo' => $comment->userInfo?$comment->userInfo->photoUrl($comment->user_id):'',
                'user_name' => $item->user ? $item->user->getName() : '',
                'contents' => $item->contents
            ];
        }
        $datas['list'][] = [
            'id' => $comment->id,
            'user_photo' => $comment->userInfo?$comment->userInfo->photoUrl($comment->user_id):'',
            'comment_count' => $comment->comment_count ?: 0,
            'like_count' => $comment->like_count,
            'user_name' => $comment->user ? $comment->user->getName() : '',
            'contents' => $comment->contents,
            'reply' => $data,
            'date' => Helper::tranTime($comment->created_at),
        ];
        return [$datas, 1];
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

    public static function findProductModel($product_id)
    {
        if (($model = Product::findOne($product_id)) !== null) {
            return $model;
        } else {
            exit;
        }
    }

}
