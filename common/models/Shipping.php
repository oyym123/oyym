<?php
namespace common\models;

use Yii;

/**
 * 快递公司
 */
class Shipping extends Base
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /** 获取快递公司 */
    public static function shippingCompany()
    {
        return require(Yii::getAlias('@common') . '/MysqlFile/kuaidi.php');
    }

    /** 快递信息html接口 */
    public function shippingInfo($params)
    {
        return "https://m.kuaidi100.com/index_all.html?type={$params['company']}&postid={$params['number']}";
    }

    /** 快递日志 */
    public static function shippingLogs($company, $number)
    {
        return [
            [
                'tile' => '已签收',
                'date_time' => '2017-07-12 12:12',
            ],
            [
                'tile' => '已收件',
                'date_time' => '2017-07-02 12:12',
            ]
        ];
    }
}
