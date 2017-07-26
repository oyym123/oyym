<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $subtitle
 * @property integer $title
 * @property integer $sort
 * @property string $sort_by_pinyin
 * @property integer $created_at
 * @property integer $updated_at
 */
class Tag extends Base
{
    const TYPE_CLASS = 1;
    const TYPE_DATE = 2;
    const TYPE_ADDRESS = 3;
    const TYPE_CONTENTS = 4;
    const TYPE_XINGSHI = 5;
    const TYPE_CONTEST_LEVEL = 6;
    const TYPE_SUBJECT = 7;
    const TYPE_GAME_FORM = 8;
    const TYPE_CONTESTTANTS = 9;

    const TAG_WITH_SERVICE_1 = 1; // 线下课程
    const TAG_WITH_SERVICE_2 = 2; // 在线课程
    const TAG_WITH_SERVICE_3 = 3; // 线下和线上课程

    /** 标签类型 */
    public static $type = [
        1 => '类别',
        2 => '时间',
        3 => '地点',
        4 => '内容',
        5 => '形式',
        6 => '参赛级别',
        7 => '竞赛学科',
        8 => '比赛形式',
        9 => '参赛对象',
    ];

    /** 标签所属服务类型 */
    public static $tagWithServices = [
        self::TAG_WITH_SERVICE_1 => '线下课程',
        self::TAG_WITH_SERVICE_2 => '在线课程',
        self::TAG_WITH_SERVICE_3 => '线下和线上课程'
    ];

    public static $typeField = [
        1 => 'class',
        2 => 'date',
        3 => 'address',
        4 => 'contents',
        5 => 'xingshi',
        6 => 'contest_level',
        7 => 'subject',
        8 => 'game_form',
        9 => 'contesttants',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'subtitle', 'title', 'service_type'], 'required'],
            ['title', 'unique', 'targetClass' => '\common\models\Download', 'message' => '标签已存在'],
            [['sort', 'sort_by_pinyin'], 'default', 'value' => '0'],
            [['status'], 'default', 'value' => '1'],
            [['type', 'sort', 'created_at', 'updated_at'], 'integer'],
            [['subtitle'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', '标签类型'),
            'status' => Yii::t('app', '状态'),
            'subtitle' => Yii::t('app', '短标题'),
            'title' => Yii::t('app', '筛选项名称'),
            'sort' => Yii::t('app', '排序'),
            'service_type' => Yii::t('app', '标签所属产品'),
            'sort_by_pinyin' => Yii::t('app', '拼音排序'),
            'created_at' => Yii::t('app', '添加于'),
            'updated_at' => Yii::t('app', '更新于'),
        ];
    }

    /** 取类型 */
    public function getType() {
        return ArrayHelper::getValue(self::$type, $this->type);
    }

    /** 通过类型取标签数据 */
    public static function getTagByType($type) {
        $query = Tag::find();
        $query->andWhere(['type' => $type]);
        $query->orderBy('sort_by_pinyin desc, sort');
        $items = $query->asArray()->all();

        return ArrayHelper::map($items, 'id', 'subtitle');
    }


    public function getTagCoupon() {
        return $this->hasMany(TagCoupon::className(), ['tid' => 'id']);
    }

    /** 获取标签名字 */
    public static function getTagsWithType() {
        $tags = Tag::find()->where(['status' => \common\models\Base::STATUS_ENABLE])->orderBy('type')->all();
        $r = [];
        foreach ($tags as $tag) {
            $r[$tag->id] = self::$type[$tag->type] . " - $tag->title";
        }

        return $r;
    }

    /**
     * Name: getTagConfig
     * Desc: 取 专利论文 | 社会活动 | 科研创新 | 备考无忧 页面头图
     * User: lixinxin <ulee@fangdazhongxin.com>
     * Date: 2017-01-21
     * @param $params
     * @return mixed
     */
    public static function getTagConfig($params) {
        // 因为设计没有出图,暂时用专利论文的图来展示
        $tagImages = [
            '专利论文'  => [
                'image' => Yii::$app->params['qiniu_url_images'] . 'banner_zhuanli_shenqing.png',
                'color' => '#568cbf',
            ],
            '社会活动'  => [
                'image' => Yii::$app->params['qiniu_url_images'] . 'banner_shehui_huodong.png',
                'color' => '#1689BF',
            ],
            '科研创新'  => [
                'image' => Yii::$app->params['qiniu_url_images'] . 'banner_keyan_chuangxin.png',
                'color' => '#5f9f7e',
            ],
            '备考无忧'  => [
                'image' => Yii::$app->params['qiniu_url_images'] . 'banner_beikao_wuyou.png',
                'color' => '#c2818a',
            ]
        ];

        return ArrayHelper::getValue($tagImages, $params);
    }
}
