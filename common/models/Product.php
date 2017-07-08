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
    const STATUS_NOT_SALE = 1; // 未上架
    const STATUS_IN_PROGRESS = 10; // 进行中
    const STATUS_WAIT_LOTTERY = 20; // 待揭晓
    const STATUS_PUBLISHED = 30; // 已揭晓
    const STATUS_CANCELED = 40; // 已取消
    const STATUS_COMPLETED = 50; // 已完成


    /** 宝贝状态 */
    public static $status = [
        self::STATUS_NOT_SALE => '未上架',
        self::STATUS_IN_PROGRESS => '进行中',
        self::STATUS_WAIT_LOTTERY => '待揭晓',
        self::STATUS_PUBLISHED => '已揭晓',
        self::STATUS_CANCELED => '已取消',
        self::STATUS_COMPLETED => '已完成',
    ];

    const TYPE_NOT_EXAMINE = 0; //未审核
    const TYPE_ALREADY_EXAMINE = 1; //已审核

    /** 审核状态(后台使用) */
    public static $examineStatus = [
        self::TYPE_NOT_EXAMINE => '未审核',
        self::TYPE_ALREADY_EXAMINE => '已审核',
    ];

    const MODEL_NUMBER = 1; // 数量模式
    const MODEL_TIME = 2; // 时间模式

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

    /**
     * 判断商品是否可以购买
     */
    public function canBuy($userId = 0)
    {
        if ($this->status != self::STATUS_IN_PROGRESS) {
            return [1, '宝贝状态不允许购买'];
        }

        if ($userId && $userId == $this->created_by) {
            return [1, '不允许购买自己的宝贝哦'];
        }
        return [0, ''];
    }

    /**
     * 是否可以修改, 如果有交易产生,则不允许修改
     */
    public function canUpdate($userId = 0)
    {
        if ($this->status != self::STATUS_IN_PROGRESS) {
            return [1, '宝贝状态不允许修改哦'];
        }

        if ($userId && $userId != $this->created_by) {
            return [1, '不允许修改别人的宝贝哦'];
        }
        return [0, ''];
    }

    /**
     * 是否可以修改, 如果有交易产生,则不允许修改
     */
    public function canDelete($userId)
    {
        if ($this->status == self::STATUS_IN_PROGRESS) {
            return [1, '宝贝状态不允许删除哦'];
        }

        if ($userId && $userId != $this->created_by) {
            return [1, '不允许删除别人的宝贝哦'];
        }
        return [0, ''];
    }


    /** 商品头图 */
    public function headImg()
    {

    }
}
