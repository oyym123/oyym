<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%tag_coupon}}".
 *
 * @property integer $id
 * @property integer $tid
 * @property integer $coupon_id
 * @property string $created_at
 * @property string $updated_at
 */
class TagCoupon extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag_coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tid', 'coupon_id'], 'integer'],
            [['created_at', 'updated_at'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tid' => Yii::t('app', '产品标签id'),
            'coupon_id' => Yii::t('app', '优惠券id'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function productCoupon()
    {

    }

    /** 取得优惠券信息 */
    public function getCoupon()
    {
        $this->hasOne(Coupon::className(), ['id' => 'coupon_id']);
    }

    public function getTag() {
        return $this->hasOne(Tag::className(), ['id' => 'tid']);
    }

    /** 保存优惠券和标签关系 */
    public function saveTagCouponRelation($coupon, $isNewRecord = true)
    {
        if ($isNewRecord) {
            foreach ($this->tid as $tagId) {
                $tagCoupon = new TagCoupon();
                $tagCoupon->tid = $tagId;
                $tagCoupon->coupon_id = $coupon->id;
                if (!$tagCoupon->save()) {
                    return false;
                }
            }
        } else {
            $tids = $coupon->getTagIds(); // 数据库中已经有的

            if ($this->tid) {
                foreach ((array)$this->tid as $tagId) {
                    if (($searchKey = array_search($tagId, $tids)) !== false) {
                        ArrayHelper::remove($tids, $searchKey);
                    } else {
                        $tagCoupon = new TagCoupon();
                        $tagCoupon->tid = $tagId;
                        $tagCoupon->coupon_id = $coupon->id;
                        if (!$tagCoupon->save()) {
                            return false;
                        }
                    }

                }
            }

            if ($tids) {
                if (!TagCoupon::deleteAll(['tid' => $tids, 'coupon_id' => $coupon->id])) {
                    return false;
                }
            }
        }

        return true;
    }
}
